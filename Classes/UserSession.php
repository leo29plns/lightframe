<?php

namespace lightframe;

class UserSession
{
    private const SESSION_NAME = \LF_PREFIX . '_SESSID';

    public function start() : void
    {
        session_name(self::SESSION_NAME);
        session_start();

        if (!isset($_SESSION['LF_BROWSERFINGERPRINT'])) {
            session_unset();

            $browserFingerprint = Fingerprint::getBrowser();
            $now = time();

            $_SESSION['LF_BROWSERFINGERPRINT'] = $browserFingerprint;
            $_SESSION['LF_TIMESTAMP'] = $now;
        }
    }

    public function stop() : void
    {
        session_destroy();
    }
}