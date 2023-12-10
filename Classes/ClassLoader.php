<?php

namespace lightframe;

class ClassLoader
{
    private static function loadClass(string $classpath, string $alias = '', string $directory = 'Classes') : void
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
            
            class_alias('lightframe\\' . $dirname . $fileInfo['filename'], $alias);
        }
    }

    public static function load(string $classpath, string $alias = '') : void
    {
        self::loadClass($classpath, $alias);
    }

    public static function loadLibrary(string $classpath) : void
    {
        self::loadClass($classpath, '', 'libraries');
    }
}