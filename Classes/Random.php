<?php

namespace lightframe;

class Random
{
    public static function hex(int $length = 32) : string
    {
        return bin2hex(self::bin($length / 2));
    }

    public static function bin(int $length = 16) : string
    {
        return random_bytes($length);
    }
}