<?php

require 'vendor/autoload.php';

function pr($str) {
    echo '<pre>';
    print_r($str);
    echo '</pre>';
}
$router = new App\Router\Router($_GET['url']);

$router->get('/posts', function() {
    echo 'tous les article';
});
$router->get('/posts/:id-:slug', function($id, $slug) {
    echo 'tous les article ' . $id;
})->with('id', '[0-9]+')->with('id', '[a-z\-0-9]+');
$router->get('/posts/:id', function($id) {
    echo 'tous les article ' . $id;
});
$router->get('/', 'Api\\Teste');
$router->post('/posts/:id', 'Posts#show');
$router->post('/posts/:id', function($id) {
    echo 'tous les article ' . $id;
});

$router->run();
