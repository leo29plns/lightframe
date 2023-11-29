<?php

namespace lightframe;

class Router
{
    private const ROUTES_FILE = 'routes.yml';
    private const CLASS_ALIAS = '\\Route\\';

    private static $routes = [];
    private static $webroot;

    public static $uri;
    public static $method;

    private static function loadRoutes() : void
    {
        self::$routes = (\Spyc\Spyc::YAMLLoad(self::ROUTES_FILE))['routes'];
    }

    private static function controllerReconciliation() : ?array
    {
        $result['index'] = null;
        $result['params'] = null;

        foreach (self::$routes as $routeIndex => $route) {
            if (substr($route['path'], -1) === '*') {
                $query = substr($route['path'], 0, -1);

                if ((strpos(self::$uri, $query) === 0) && (strlen(self::$uri) > strlen($query)) && ($route['method'] === self::$method)) {
                    $result['index'] = $routeIndex;
                    $result['params'] = explode('/', substr(substr(self::$uri, strlen($query)), 0, -1));
                }
            } elseif (($route['path'] === self::$uri) && ($route['method'] === self::$method)) {
                $result['index'] = $routeIndex;
            }
        }

        return $result['index'] !== null ? $result : null;
    }

    public static function loadController(string $requestUri, string $requestMethod) : void
    {
        if (substr($requestUri, -1) !== '/') {
            $requestUri .= '/';
        }

        self::$webroot = dirname($_SERVER['PHP_SELF']);
        self::loadRoutes();

        self::$uri = str_replace(self::$webroot, '', $requestUri);
        self::$method = $requestMethod;

        $reconciliation = self::controllerReconciliation();

        if (!is_null($reconciliation)) {
            $route = self::$routes[$reconciliation['index']];
            $params = $reconciliation['params'];

            $controller = explode('->', $route['controller']);
            $controllerPath = str_replace('\\', DIRECTORY_SEPARATOR, $controller[0]);

            $file = 'Controllers' . DIRECTORY_SEPARATOR . $controllerPath . '.php';
            require($file);

            $fileInfo = pathinfo($file);

            $namespace = explode(DIRECTORY_SEPARATOR, $fileInfo['dirname']);
            array_shift($namespace);
            $namespace = implode('', $namespace);
            
            $dirname = str_replace(DIRECTORY_SEPARATOR, '\\', $namespace);
            $dirname .= ($dirname !== '') ? '\\' : '';
            
            class_alias('lightframe\\Controllers\\' . $dirname . $fileInfo['filename'], self::CLASS_ALIAS . $controller[0]);

            $controllerInstance = new (self::CLASS_ALIAS . $controller[0]);
            call_user_func([$controllerInstance, $controller[1]], $params);
        } else {
            \Debug::dump('404 Unknown ' . self::$uri, true);
        }
    }
}