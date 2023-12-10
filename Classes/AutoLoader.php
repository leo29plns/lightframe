<?php

namespace lightframe;

class AutoLoader
{
    private static $autoLoadNamespaces = ['Components'];

    public static function loadClass($class)
    {
        $classContext = explode('\\', $class);
        $classPath = implode(DIRECTORY_SEPARATOR, $classContext);
        $className = end($classContext);

        if (in_array($classContext[0], self::$autoLoadNamespaces)) {
            switch (true) {
                case $classContext[0] === 'Components':
                    $file = 'Classes' . DIRECTORY_SEPARATOR . 'Html' . DIRECTORY_SEPARATOR . $classPath . '.php';
                    if (file_exists($file)) {
                        include $file;
                        class_alias('lightframe\\Html\\' . $class, $class);
                    }
                    break;
            }    
        }
    }
}