<?php

namespace lightframe;

class Fingerprint
{
    public static function getBrowser() : string
    {
        $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'noUserAgent';
        $acceptLanguage = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'noAcceptLanguage';
        $acceptEncoding = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : 'noAcceptEncodingValue';
        $connection = isset($_SERVER['HTTP_CONNECTION']) ? $_SERVER['HTTP_CONNECTION'] : 'noConnectionReturn';
        $remoteAddress = $_SERVER['REMOTE_ADDR'];

        return hash('sha256', $userAgent . $acceptLanguage . $acceptEncoding . $connection . $remoteAddress);
    }
}