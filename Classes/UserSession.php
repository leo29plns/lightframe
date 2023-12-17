<?php

namespace lightframe;

class UserSession
{
    private const SESSION_NAME = \LF_PREFIX . '_SESSID';

    public function start() : void
    {
        session_name(self::SESSION_NAME);
        ini_set('session.gc_maxlifetime', 3600);
        session_start();

        if (!isset($_SESSION['LF_BROWSERFINGERPRINT'])) {
            session_unset();

            $browserFingerprint = Fingerprint::getBrowser();
            $now = time();

            $_SESSION['LF_BROWSERFINGERPRINT'] = $browserFingerprint;
            $_SESSION['LF_TIMESTAMP'] = $now;
            
            \Token::generate();
        }
    }

    public function stop() : void
    {
        session_destroy();
    }
}