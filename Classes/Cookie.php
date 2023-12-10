<?php

namespace lightframe;

class Cookie
{
    private const COOKIE_PREFIX = \LF_PREFIX . '_';

    public function setCookie(string $name, string $value, int $expiration = 0, bool $httpOnly = false) : void
    {
        $cookieName = self::COOKIE_PREFIX . $name;
        $secure = ($_ENV['LF_DEBUG'] === 'false') ? false : true;
        setcookie($cookieName, $value, ($expiration === 0) ? 0 : (time() + $expiration), '/', '', $secure, $httpOnly);
    }

    public function getCookie(string $name) : ?string
    {
        $cookieName = self::COOKIE_PREFIX . $name;
        return isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : null;
    }

    public function deleteCookie(string $name) : void
    {
        $cookieName = $this->cookiePrefix . $name;
        setcookie($cookieName, '', time() - 3600, '/');
        unset($_COOKIE[$cookieName]);
    }
}