<?php

namespace lightframe;

class FunctionLoader
{
    public static function load(string $file) : void
    {
        require('functions' . DIRECTORY_SEPARATOR . $file);
    }
}