<?php

# Loads ClassLoader
require('Classes' . DIRECTORY_SEPARATOR . 'ClassLoader.php');
use lightframe\ClassLoader;

ClassLoader::load('Initialization'. DIRECTORY_SEPARATOR . 'Dotenv.php');
lightframe\Initialization\Dotenv::load('.env');

# Loads debug() and enables PHP errors
if ($_ENV['APP_DEBUG'] === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    ClassLoader::load('Initialization'. DIRECTORY_SEPARATOR . 'Debug.php', 'Debug');
}

# Loads YAML parser
require('libraries' . DIRECTORY_SEPARATOR . 'Spyc.php');

ClassLoader::load('Parser.php', 'Parser');

ClassLoader::load('Router.php', 'Router');
Router::loadController($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);