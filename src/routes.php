<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/pesquisa', 'HomeController@pesquisa');
$router->get('/perfil', 'HomeController@perfil');
$router->get('/amigos', 'HomeController@amigos');
$router->get('/fotos', 'HomeController@fotos');
$router->get('/config', 'HomeController@config');
$router->get('/sair', 'HomeController@sair');

$router->get('/login', 'LoginController@signin');
$router->post('/login', 'LoginController@signinAction');

$router->get('/cadastro', 'LoginController@signup');
$router->post('/cadastro', 'LoginController@signupAtcion');

$router->post('/post/new', 'PostController@new');
