<?php

namespace lightframe\Controllers;

class Home
{
    public function home() : void
    {
        \Debug::dump('Je suis dans Home, méthode home');
    }
}