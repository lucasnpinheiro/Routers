<?php

ini_set('default_charset', 'UTF-8');

function debug($str) {
    echo '<div style="padding: 25px;">';
    echo '<pre style="color:black;">';
    $a = debug_backtrace();
    echo '<div style="color:red;"><strong>File: </strong>' . $a[0]['file'] . '</div>';
    echo '<div style="color:red;"><strong>Line: </strong>' . $a[0]['line'] . '</div>';
    if (isset($a[1]['class']) and ! empty($a[1]['class'])) {
        echo '<div style="color:red;"><strong>Class: </strong>' . $a[1]['class'] . '</div>';
    }
    if (isset($a[1]['function']) and ! empty($a[1]['function'])) {
        echo '<div style="color:red;"><strong>Function: </strong>' . $a[1]['function'] . '</div>';
    }
    var_dump($str);
    echo '</pre>';
    echo '</hr>';
    echo '</div>';
}

function pr($str) {
    echo '<div style="padding: 25px;">';
    echo '<pre style="color:black;">';
    $a = debug_backtrace();
    echo '<div style="color:red;"><strong>File: </strong>' . $a[0]['file'] . '</div>';
    echo '<div style="color:red;"><strong>Line: </strong>' . $a[0]['line'] . '</div>';
    if (isset($a[1]['class']) and ! empty($a[1]['class'])) {
        echo '<div style="color:red;"><strong>Class: </strong>' . $a[1]['class'] . '</div>';
    }
    if (isset($a[1]['function']) and ! empty($a[1]['function'])) {
        echo '<div style="color:red;"><strong>Function: </strong>' . $a[1]['function'] . '</div>';
    }
    print_r($str);
    echo '</pre>';
    echo '</hr>';
    echo '</div>';
}

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__) . DS);
}

if (!defined('CORE')) {
    define('CORE', ROOT . 'Core' . DS);
}

if (!defined('VENDOR')) {
    define('VENDOR', ROOT . 'vendor' . DS);
}

if (!defined('APP')) {
    define('APP', ROOT . 'src' . DS);
}

if (!defined('WEBROOT')) {
    define('WEBROOT', 'webroot' . DS);
}

require_once ROOT . 'vendor/autoload.php';

$router = new \Core\Router\Router($_GET['url']);
$router->auto('/', 'Api\Teste');
$router->auto('/add', 'Api\Teste#add');

$router->descobre();

$router->run();
