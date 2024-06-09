<?php

namespace Plugin\ScriptAssetApi\Controllers;

use CodeIgniter\API\ResponseTrait;
use Plugin\ShopifyAuth\Controllers\Api\ApiBaseController;


class ScriptTags extends ApiBaseController
{
    use ResponseTrait;

    public function create()
    {
        $shopify = $this->getShopifySDK();
       
        $newScriptUrl = base_url("script_asset/js/script.js");

        try {
            // srcパラメータを使用して特定のURLを持つScriptTagを取得
            $params = [
                'src' => $newScriptUrl,
                'limit' => 1,
            ];
            $existingScriptTags = $shopify->ScriptTag->get($params);


            // 既存のScriptTagに同じURLがあるか確認
            $scriptTagExists = !empty($existingScriptTags);

            if (!$scriptTagExists) {
                // 新しいScriptTagを作成
                $scriptTagData = [
                    'event' => 'onload',
                    'src' => $newScriptUrl,
                ];

                $newScriptTag = $shopify->ScriptTag->post($scriptTagData);
            } else {
                $newScriptTag = $existingScriptTags[0];
            }

            if (empty($newScriptTag["id"])) {
                throw new \Exception("失敗しました");
            }

        } catch (\Exception $e) {
            return $this->failServerError('サーバーエラー：' . $e->getMessage());
        }


        // error_log("info",json_encode($ScriptTagExists,JSON_PRETTY_PRINT));
        return $this->respondCreated([
            'id' => $newScriptTag["id"],
            'message' => 'ScriptTagが正常に作成されました。'
        ]);
    }
}
