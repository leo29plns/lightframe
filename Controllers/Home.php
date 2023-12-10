<?php

namespace lightframe\Controllers;

class Home
{
    public function home() : void
    {
        $view = new \View('home.php');
        // $view->setTitle('95');
        $view->phpToJs('test_value', ['1', 2, '34']);
        // $view->setDescription('Bonjour');

        echo $view->render();
    }
}