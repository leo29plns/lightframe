<?php

# Loads conf.php
require('conf.php');

# Loads ClassLoader
require('Classes' . DIRECTORY_SEPARATOR . 'ClassLoader.php');
use lightframe\ClassLoader;

ClassLoader::load('FunctionLoader.php', 'FunctionLoader');
ClassLoader::load('LfError.php', 'LfError');

ClassLoader::load('Initialization'. DIRECTORY_SEPARATOR . 'Dotenv.php');
lightframe\Initialization\Dotenv::load('.env');

# Loads debug() and enables PHP errors
if ($_ENV['LF_DEBUG'] === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    ClassLoader::load('Initialization'. DIRECTORY_SEPARATOR . 'Debug.php', 'Debug');
}

# Loads functions
// FunctionLoader::load('redirect.php');

# Loads required classes
ClassLoader::loadLibrary('Spyc.php');
ClassLoader::load('HtmlBuild' . DIRECTORY_SEPARATOR . 'Parser.php', 'Parser');
ClassLoader::load('HtmlBuild' . DIRECTORY_SEPARATOR . 'Template.php', 'Template');
ClassLoader::load('Html' . DIRECTORY_SEPARATOR . 'Async.php', 'Html\Async');
ClassLoader::load('View.php', 'View');
ClassLoader::load('PhpToJs.php', 'PhpToJs');
ClassLoader::load('Random.php', 'Random');
ClassLoader::load('Token.php', 'Token');
ClassLoader::load('Fingerprint.php', 'Fingerprint');
ClassLoader::load('StringSecure.php', 'StringSecure');
ClassLoader::load('Cookie.php', 'Cookie');
ClassLoader::load('UserSession.php', 'UserSession');
ClassLoader::load('Redirect.php', 'Redirect');
ClassLoader::load('Router.php', 'Router');

# Instantiates session
$userSession = new UserSession();
$userSession->start();

# Loads AutoLoader (eg. for components) and register it via spl
ClassLoader::load('AutoLoader.php');
spl_autoload_register(['\lightframe\AutoLoader', 'load']);

# Proceed client request
Router::loadRouteController($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], getallheaders());