<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Login::index');
$routes->get('login', 'Login::index');
$routes->post('login/entrar', 'Login::entrar');
$routes->get('salir', 'Login::salir');
$routes->get('inicio', 'Login::inicio', ['filter' => 'permiso']);
