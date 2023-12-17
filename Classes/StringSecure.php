<?php

namespace lightframe;

class StringSecure
{
    public static function hash(string $salt, string $str, int $cost = 12) : string
    {
        $salt = hash('sha256', $salt);
        $options = [
            'cost' => $cost
        ];

        $hashedStr = password_hash($salt . $str, PASSWORD_BCRYPT, $options);

        return $hashedStr;
    }

    public static function verify(string $salt, string $str, string $hash) : ?string
    {
        $salt = hash('sha256', $salt);
        $hashedStr = $salt . $str;
        $verification = password_verify($hashedStr, $hash);

        if (!$verification) {
            $verification = null;
        }

        return $verification;
    }

    public static function encrypt(string $password, string $plaintext) : string
    {
        $iv = random_bytes(16);
        $key = hash('sha256', $password, true);
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv . $ciphertext);
    }

    public static function decrypt(string $password, string $ciphertext) : ?string
    {
        $ciphertext = base64_decode($ciphertext);
        $iv = substr($ciphertext, 0, 16);
        $ciphertext = substr($ciphertext, 16);
        $key = hash('sha256', $password, true);
        $plaintext = openssl_decrypt($ciphertext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        if (!$plaintext) {
            $plaintext = null;
        }

        return $plaintext;
    }
}