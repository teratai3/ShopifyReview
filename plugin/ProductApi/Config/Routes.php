<?php
$routes->group('api/products', ['namespace' => 'Plugin\ProductApi\Controllers'], function ($routes) {
    $routes->add('/', 'Products::index');
    $routes->get('show/(:num)', 'Products::show/$1');
});