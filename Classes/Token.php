<?php

namespace lightframe;

class Token
{
    public static function generate() : void
    {
        $_SESSION['LF_TOKEN'] = \Random::hex();
    }

    public static function verify(string $token) : bool
    {
        $result = false;

        if (isset($_SESSION['LF_TOKEN']) && $_SESSION['LF_TOKEN'] === $token) {
            self::generate();
            $result = true;
        }

        return $result;
    }
}