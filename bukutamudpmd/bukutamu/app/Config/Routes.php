<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// -------------------------
// Route Publik
// -------------------------
$routes->get('/', 'Home::index');
$routes->post('home/simpan', 'Home::simpan');

// -------------------------
// Route Auth
// -------------------------
$routes->get('admin/login', 'Auth::index');
$routes->get('auth', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');
$routes->get('admin/logout', 'Auth::logout');

// -------------------------
// Route Admin
// -------------------------
$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('', 'Admin::index');

    $routes->get('export/(:any)', 'Admin::export/$1');
    $routes->get('hapus/(:num)', 'Admin::hapus/$1');
    $routes->get('qr', 'Admin::qr');
});