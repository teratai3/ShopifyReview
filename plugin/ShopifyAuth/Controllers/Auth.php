<?php

namespace Plugin\ShopifyAuth\Controllers;

use App\Controllers\BaseController;
use Plugin\ShopifyAuth\Models\ShopifyAuthModel;
use PHPShopify\ShopifySDK;
use PHPShopify\AuthHelper;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class Auth extends BaseController
{
    protected $ShopifyAuth;
    protected $shopDomain;

    protected $isAuthenticated = true;
    protected $ErrorMessage = "";

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->ShopifyAuth = new ShopifyAuthModel();

        $this->shopDomain = $this->request->getGet('shop');

        if (!$this->shopDomain) {
            $this->isAuthenticated = false;
            $this->ErrorMessage = "ショップドメインは必須です";
            return;
        }

        // ドメインの形式を確認
        if (!filter_var($this->shopDomain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            $this->isAuthenticated = false;
            $this->ErrorMessage = "無効なショップドメインです";
            return;
        }

        // ShopifySDKの設定
        ShopifySDK::config([
            'ShopUrl' => $this->shopDomain, // アプリをインストールするショップURL
            'ApiKey' => env("ShopifyApiKey"), // アプリ管理画面に表示されるAPIキー
            'SharedSecret' => env("ShopifySharedSecret"), // アプリ管理画面に表示されるAPIシークレットキー
        ]);
    }

    public function index()
    {
        if (!$this->isAuthenticated) {
            return $this->showErrorPage(401, $this->ErrorMessage);
        }

        //$scopes = 'read_products,write_products,read_script_tags,write_script_tags';
        $scopes = 'read_products,write_products,read_script_tags,write_script_tags,read_themes,write_themes';
        AuthHelper::createAuthRequest($scopes, base_url(env("ShopifyRedirectUrl")));
        exit;
    }

    public function callback()
    {
        if (!$this->isAuthenticated) {
            return $this->showErrorPage(401, $this->ErrorMessage);
        }

        $db = \Config\Database::connect();
        $db->transStart();
        try {

            $accessToken = AuthHelper::getAccessToken();

            if (!isset($accessToken)) {
                throw new \Exception('アクセストークンを取得できませんでした。');
            }

            // ここで生成したアクセストークンはDBに保存
            $shopifyAuthModel = new ShopifyAuthModel();
            $shopDomain = $this->request->getGet('shop');

            $existingRecord = $shopifyAuthModel->where('shop_domain', $shopDomain)->first();


            $result = $shopifyAuthModel->save([
                "id" => !empty($existingRecord["id"]) ? $existingRecord["id"] : null,
                "shop_domain" => $shopDomain,
                "access_token" => $accessToken,
            ]);

            if ($result === false) {
                throw new \Exception($db->error()["message"]);
            }

            $db->transCommit();
        } catch (\Exception $e) {
            $db->transRollback();
            $message = "エラー：" . $e->getMessage();
            log_message('error', $message);
            return $this->showErrorPage(500, $message);
        }


        $query = http_build_query($this->request->getGet());
        
        return redirect()->to(base_url("/?{$query}"));
    }


    protected function showErrorPage($statusCode, $message)
    {
        return $this->response->setStatusCode($statusCode)->setBody(view('Plugin\ShopifyAuth\Views\error', ["statusCode" => $statusCode, 'message' => $message]));
    }
}
