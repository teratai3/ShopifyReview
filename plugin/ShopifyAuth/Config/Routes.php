<?php
$routes->group('auth', ['namespace' => 'Plugin\ShopifyAuth\Controllers'], function ($routes) {
    $routes->add('/', 'Auth::index');
    $routes->add('callback', 'Auth::callback');
});