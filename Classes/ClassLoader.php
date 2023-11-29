<?php

namespace lightframe;

class ClassLoader
{
    public static function load(string $classpath, string $alias = '') : void {
        $file = 'Classes' . DIRECTORY_SEPARATOR . $classpath;
        require_once($file);
        
        if (!empty($alias)) {
            $fileInfo = pathinfo($file);

            $namespace = explode(DIRECTORY_SEPARATOR, $fileInfo['dirname']);
            array_shift($namespace);
            $namespace = implode('', $namespace);
            
            $dirname = str_replace(DIRECTORY_SEPARATOR, '\\', $namespace);
            $dirname .= ($dirname !== '') ? '\\' : '';
            
            class_alias('lightframe\\' . $dirname . $fileInfo['filename'], $alias);
        }
    }

    public static function loadAll(string $classdirpath) : void {
        $files = scandir($classdirpath);
        
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $classfilepath = $classdirpath . DIRECTORY_SEPARATOR . $file;
                $classinfo = pathinfo($classfilepath);
                if (isset($classinfo['extension']) && $classinfo['extension'] === 'php') {
                    self::load($classfilepath);
                }
            }
        }
    }
}