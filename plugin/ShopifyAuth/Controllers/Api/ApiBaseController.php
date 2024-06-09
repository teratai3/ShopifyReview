<?php

namespace Plugin\ShopifyAuth\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use PHPShopify\ShopifySDK;

class ApiBaseController extends ResourceController
{
    use ResponseTrait;
    private $shopifySDK;
    private $shopId;

    public function __construct()
    {
        $this->initShopify();
    }

    private function initShopify()
    {
        try {
            $request = service('request');
            $shopDomain = $request->myShopDomain;
            $accessToken = $request->myAccessToken;

            $this->shopId = $request->myShopId;
            $config = [
                'ShopUrl' => $shopDomain,
                'AccessToken' => $accessToken,
            ];
            $this->shopifySDK = new ShopifySDK($config);
           
        } catch (\Exception $e) {
            $message = "Shopify SDKの初期化に失敗しました:" . $e->getMessage();
            log_message('error', $message);
            $response = service('response');
            $response->setStatusCode(500)->setJSON(["error" => $message])->send();
            exit;
        }
    }

    

    protected function getShopifySDK()
    {
        return $this->shopifySDK;
    }

    protected function getShopId()
    {
        return $this->shopId;
    }
}
