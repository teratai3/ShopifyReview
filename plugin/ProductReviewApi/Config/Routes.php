<?php
$routes->group('api/product_reviews', ['namespace' => 'Plugin\ProductReviewApi\Controllers'], function ($routes) {
    $routes->get('/', 'ProductReviews::index');
    $routes->get('show/(:num)', 'ProductReviews::show/$1');
    $routes->post('create', 'ProductReviews::create');
    $routes->put('update/(:num)', 'ProductReviews::update/$1');
    $routes->delete('delete/(:num)', 'ProductReviews::delete/$1');
});

$routes->group('front_api/product_reviews', ['namespace' => 'Plugin\ProductReviewApi\Controllers\Front'], function ($routes) {
    $routes->get('/', 'ProductReviews::index');
    $routes->post('create', 'ProductReviews::create');
});