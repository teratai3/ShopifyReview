<?php

namespace Plugin\ShopifyAuth\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Plugin\ShopifyAuth\Models\ShopifyAuthModel;
use Plugin\ShopifyAuth\Services\TokenService;
use CodeIgniter\HTTP\Response;

class ShopifyAuthFilter implements FilterInterface
{

    protected $response;

    public function __construct()
    {
        $this->response = service('response');
    }

    public function before(RequestInterface $request, $arguments = null)
    {

        $token = $this->getAuthorizationHeader();

        $TokenService = new TokenService(env("ShopifyApiKey"), env("ShopifySharedSecret"));

        $result = $TokenService->verifySessionToken($token);

        if (!$result['status']) {
            return $this->denyAccess($result['message']);
        }



        $shopDomain = parse_url($result['payload']['dest'], PHP_URL_HOST);
        $shopifyAuthModel = new ShopifyAuthModel();
        $record = $shopifyAuthModel->where('shop_domain', $shopDomain)->first();

        // $logger = service('logger');
        // $logger->error($shopDomain);

        if (!$record || !isset($record['access_token'])) {
            return $this->denyAccess('アクセストークンが見つかりませんでした');
        }

        $request->myShopDomain = $shopDomain;
        $request->myShopId = $record["id"];

        // log_message('info', 'test: ' . $record["id"]);
        $request->myAccessToken = $record['access_token'];

        return $request;
    }


    private function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }

    protected function denyAccess($message, $statusCode = Response::HTTP_UNAUTHORIZED)
    {
        // アクセス拒否のレスポンスを設定
        $this->response->setStatusCode($statusCode);
        $this->response->setContentType('application/json');
        $this->response->setBody(json_encode(['error' => $message]));
        return $this->response;
    }
}
