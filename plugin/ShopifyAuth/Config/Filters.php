<?php

//同ドメインからしかfetchしないため、コメントアウト
// $filters->aliases['corsFilter'] =  \Plugin\ShopifyAuth\Filters\CorsFilter::class;

// $filters->filters["corsFilter"] = [
//     "before" => '/api*'
// ];

$filters->aliases['rateLimitFilter'] =  \Plugin\ShopifyAuth\Filters\RateLimitFilter::class;
$filters->filters["rateLimitFilter"] = [
    "before" => '/api/*'
];

$filters->aliases['shopifyAuthFilter'] =  \Plugin\ShopifyAuth\Filters\ShopifyAuthFilter::class;
$filters->filters["shopifyAuthFilter"] = [
    "before" => '/api/*'
];


$filters->aliases['shopifyFrontAuthFilter'] =  \Plugin\ShopifyAuth\Filters\ShopifyFrontAuthFilter::class;
$filters->filters["shopifyFrontAuthFilter"] = [
    "before" => '/front_api/*'
];



// $filters->filters["shopifyAuthFilter"] = [
//     "before" => [
//         '/api/*',
//         '/front_api/*'
//     ]
// ];