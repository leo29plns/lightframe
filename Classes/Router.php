<?php

namespace lightframe;

class Router
{
    private const ROUTES_FILE = 'routes.yml';
    private const CLASS_ALIAS = '\\Route\\';

    private static $routes = [];
    private static $webroot;

    public static $uri;
    public static $locale;
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

        return ($result['index'] !== null) ? $result : null;
    }

    private static function loadController(string $classpath, string $alias = '', string $directory = 'Controllers') : void
    {
        $file = $directory . DIRECTORY_SEPARATOR . $classpath;
        require($file);
        
        if (!empty($alias)) {
            $fileInfo = pathinfo($file);

            $namespace = explode(DIRECTORY_SEPARATOR, $fileInfo['dirname']);
            array_shift($namespace);
            $namespace = implode('', $namespace);
            
            $dirname = str_replace(DIRECTORY_SEPARATOR, '\\', $namespace);
            $dirname .= ($dirname !== '') ? '\\' : '';
            
            class_alias('lightframe\\Controllers\\' . $dirname . $fileInfo['filename'], $alias);
        }
    }

    public static function loadRouteController(string $requestUri, string $requestMethod) : void
    {
        $requestUri = parse_url($requestUri, PHP_URL_PATH);

        if (substr($requestUri, -1) !== '/') {
            $requestUri .= '/';
        }

        self::$webroot = dirname($_SERVER['PHP_SELF']);
        $_ENV['LF_WEBROOT'] = self::$webroot;
        self::loadRoutes();

        $uri = (self::$webroot !== '/') ? substr($requestUri, strlen(self::$webroot)) : $requestUri;
        self::$method = $requestMethod;

        $parts = explode('/', trim($uri, '/'));

        if (isset($parts[0]) && strlen($parts[0]) === 2) {
            $locale = $parts[0];

            $localesFile = json_decode(file_get_contents('locales/locales.json'), true);
            $_ENV['LF_LOCALES_FILE'] = $localesFile;
            
            $locales = array_map(function ($key) {
                return $key['code'];
            }, $localesFile['locales']);

            if (!in_array($locale, $locales)) {
                $urlWithLocale = self::$webroot . '/' . \LF_DEFAULT_LOCALE . $uri;
                \Redirect::url($urlWithLocale);
            }
            
            self::$locale = $locale;
            $_SESSION['LF_LOCALE'] = self::$locale;
            self::$uri = substr($uri, strlen(self::$locale) + 1);
        } else {
            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                $locale = substr(explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE'])[0], 0, 2);
            } else {
                $locale = \LF_DEFAULT_LOCALE;
            }
            $urlWithLocale = self::$webroot . '/' . $locale . $uri;
            \Redirect::url($urlWithLocale);
        }

        $reconciliation = self::controllerReconciliation();

        if (!is_null($reconciliation)) {
            $route = self::$routes[$reconciliation['index']];
            $params = $reconciliation['params'];

            $controller = explode('->', $route['controller']);
            $controllerPath = str_replace('\\', DIRECTORY_SEPARATOR, $controller[0]);

            self::loadController($controllerPath . '.php', self::CLASS_ALIAS . $controller[0]);
            
            $controllerInstance = new (self::CLASS_ALIAS . $controller[0]);
            call_user_func([$controllerInstance, $controller[1]], $params);
        } else {
            \LfError::exit('page', 'not_found', 404);
        }
    }
}