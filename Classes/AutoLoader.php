<?php

namespace lightframe;

class AutoLoader
{
    private static $autoLoadNamespaces = ['Interfaces', 'Components'];

    public static function load($file)
    {
        $fileContext = explode('\\', $file);
        $filePath = implode(DIRECTORY_SEPARATOR, $fileContext);
        $fileName = end($fileContext);

        if (in_array($fileContext[0], self::$autoLoadNamespaces)) {
            switch (true) {
                case $fileContext[0] === 'Interfaces':
                    $effectiveFile = $filePath . '.php';
                    if (file_exists($effectiveFile)) {
                        include $effectiveFile;
                        class_alias('lightframe\\' . $file, $file);
                    }
                    break;
                case $fileContext[0] === 'Components':
                    $effectiveFile = 'Classes' . DIRECTORY_SEPARATOR . 'Html' . DIRECTORY_SEPARATOR . $filePath . '.php';
                    if (file_exists($effectiveFile)) {
                        include $effectiveFile;
                        class_alias('lightframe\\Html\\' . $file, $file);
                    }
                    break;
            }
        }
    }
}