<?php

namespace Plugin\ProductApi\Controllers;

use CodeIgniter\API\ResponseTrait;
use Plugin\ShopifyAuth\Controllers\Api\ApiBaseController;


class Products extends ApiBaseController
{
    use ResponseTrait;

    public function index(){

        $params = $this->request->getGet();

        try {
            $data = $this->getShopifySDK()->Product->get($params);
        } catch (\Exception $e) {
            return $this->fail(["error" => $e->getMessage()], 404);
        }

        return $this->respond([
            "data" => $data,
        ]);
    }

    public function show($id = 0){
        try {
            $data = $this->getShopifySDK()->Product($id)->get();
        } catch (\Exception $e) {
            return $this->fail(["error" => $e->getMessage()], 404);
        }
    
        return $this->respond([
            "data" => $data,
        ]);
    }
}
