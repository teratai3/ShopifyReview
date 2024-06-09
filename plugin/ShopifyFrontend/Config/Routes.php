<?php
$routes->group('/', ['namespace' => 'Plugin\ShopifyFrontend\Controllers'], function ($routes) {
    $routes->add('', 'Shopify::index');
    $routes->get('shopify_frontend/(:segment)/(:any)', 'AssetController::fetchAsset/$1/$2');
});

$routes->set404Override('Plugin\ShopifyFrontend\Controllers\Shopify::index'); 