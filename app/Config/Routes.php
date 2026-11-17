<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Login::index');
$routes->get('login', 'Login::index');
$routes->post('login/entrar', 'Login::entrar');
$routes->get('salir', 'Login::salir');
$routes->get('inicio', 'Login::inicio', ['filter' => 'permiso']);

// Panel de Administrador
$routes->group('', ['filter' => 'permiso:Administrador'], function ($routes) {
    // Usuarios
    $routes->get('usuarios', 'Usuarios::index');
    $routes->get('usuarios/nuevo', 'Usuarios::nuevo');
    $routes->post('usuarios/insertar', 'Usuarios::insertar');
    $routes->get('usuarios/editar/(:num)', 'Usuarios::editar/$1');
    $routes->post('usuarios/actualizar', 'Usuarios::actualizar');
    $routes->get('usuarios/eliminar/(:num)', 'Usuarios::eliminar/$1');
    $routes->post('usuarios/borrar/(:num)', 'Usuarios::borrar/$1');

    // Mesas
    $routes->get('mesas', 'Mesas::index');
    $routes->get('mesas/nuevo', 'Mesas::nuevo');
    $routes->post('mesas/insertar', 'Mesas::insertar');
    $routes->get('mesas/editar/(:num)', 'Mesas::editar/$1');
    $routes->post('mesas/actualizar', 'Mesas::actualizar');
    $routes->get('mesas/eliminar/(:num)', 'Mesas::eliminar/$1');
    $routes->post('mesas/borrar/(:num)', 'Mesas::borrar/$1');

    // Categorías
    $routes->get('categorias', 'Categorias::index');
    $routes->get('categorias/nuevo', 'Categorias::nuevo');
    $routes->post('categorias/insertar', 'Categorias::insertar');
    $routes->get('categorias/editar/(:num)', 'Categorias::editar/$1');
    $routes->post('categorias/actualizar', 'Categorias::actualizar');
    $routes->get('categorias/eliminar/(:num)', 'Categorias::eliminar/$1');
    $routes->post('categorias/borrar/(:num)', 'Categorias::borrar/$1');

    // Productos
    $routes->get('productos', 'Productos::index');
    $routes->get('productos/nuevo', 'Productos::nuevo');
    $routes->post('productos/insertar', 'Productos::insertar');
    $routes->get('productos/editar/(:num)', 'Productos::editar/$1');
    $routes->post('productos/actualizar', 'Productos::actualizar');
    $routes->get('productos/eliminar/(:num)', 'Productos::eliminar/$1');
    $routes->post('productos/borrar/(:num)', 'Productos::borrar/$1');

    
});

// Panel del mozo
$routes->group('mozo', ['filter' => 'permiso:Mozo'], function ($routes) {
    $routes->get('/', 'Mozo::index');
    $routes->get('mesa/(:num)', 'Mozo::mesa/$1');
    $routes->get('avisos', 'Mozo::avisos');
    $routes->post('abrir', 'Mozo::abrir');
    $routes->post('enviar', 'Mozo::enviar');
    $routes->post('entregar/(:num)', 'Mozo::entregar/$1');
    $routes->post('cancelarcomanda/(:num)', 'Mozo::cancelarcomanda/$1');
    $routes->post('cerrar', 'Mozo::cerrar');
});

// Panel de cocina
$routes->group('cocina', ['filter' => 'permiso:Cocina'], function ($routes) {
    $routes->get('/', 'Cocina::index');
    $routes->get('listar', 'Cocina::listar');
    $routes->post('cambiarestado', 'Cocina::cambiarestado');
});

// Historial de comandas
$routes->group('historial', ['filter' => 'permiso:Administrador,Cocina'], function ($routes) {
    $routes->get('/', 'Historial::index');
    $routes->get('ver/(:num)', 'Historial::ver/$1');
});