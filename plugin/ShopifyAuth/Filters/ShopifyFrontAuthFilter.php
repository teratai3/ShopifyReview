<?php

namespace Plugin\ShopifyAuth\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Plugin\ShopifyAuth\Models\ShopifyAuthModel;
use Plugin\ShopifyAuth\Services\TokenService;
use CodeIgniter\HTTP\Response;

class ShopifyFrontAuthFilter implements FilterInterface
{

    protected $response;

    public function __construct()
    {
        $this->response = service('response');
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $query_string = $_SERVER['QUERY_STRING'];
        parse_str($query_string, $query_hash);

        // "signature"エントリを削除して保存
        if (!isset($query_hash['signature'])) {
            return $this->denyAccess('署名がありません',400);
        }

        $signature = $query_hash['signature'];
        unset($query_hash['signature']);

        // パラメータをソートして、クエリ形式に変換
        ksort($query_hash);
        $sorted_params = [];
        foreach ($query_hash as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $sorted_params[] = $key . '=' . $value;
        }
        
        $sorted_params_string = implode('', $sorted_params);

        // HMAC-SHA256署名を計算
        $calculated_signature = hash_hmac('sha256', $sorted_params_string,env("ShopifySharedSecret"));

        // 署名を比較して検証
        if (!hash_equals($signature, $calculated_signature)) {
            return $this->denyAccess('無効な署名',401);
        }

        $shopifyAuthModel = new ShopifyAuthModel();
        $record = $shopifyAuthModel->where('shop_domain',$request->getGet('shop'))->first();

        if (!$record || !isset($record['access_token'])) {
            return $this->denyAccess('アクセストークンが見つかりませんでした');
        }

        $request->myShopDomain = $record['shop_domain'];
        $request->myShopId = $record["id"];
        $request->myAccessToken = $record['access_token'];
        
        return $request;
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
