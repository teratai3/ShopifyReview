<?php

namespace Plugin\ShopifyFrontend\Controllers;

use App\Controllers\BaseController;

class Shopify extends BaseController
{
    public function index()
    {
        return view('Plugin\ShopifyFrontend\Views\Shopify\index',[
            'apiKey' => env("ShopifyApiKey"),
            'shopOrigin' => !empty($this->request->getGet('shop')) ? $this->request->getGet('shop') : "",
            'host' => !empty($this->request->getGet('host')) ? $this->request->getGet('host') : ""
        ]);
    }
}
