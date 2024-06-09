<?php
$routes->group('api/script_tags', ['namespace' => 'Plugin\ScriptAssetApi\Controllers'], function ($routes) {
    $routes->post('create', 'ScriptTags::create');
});

$routes->group('/', ['namespace' => 'Plugin\ScriptAssetApi\Controllers'], function ($routes) {
    $routes->get('script_asset/(:segment)/(:any)', 'AssetController::fetchAsset/$1/$2');
});

$routes->group('api/assets', ['namespace' => 'Plugin\ScriptAssetApi\Controllers'], function ($routes) {
    $routes->get('review', 'Assets::review');
});