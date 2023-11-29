<?php

namespace lightframe\Controllers\About;

class Instagram
{
    public function instagramParams(array $param) : void
    {
        \Debug::dump($param, 'Je suis dans Instagram, méthode instagramParams');
    }
}