<?php

namespace Plugin\ProductReviewApi\Config;

use PHPShopify\ShopifySDK;

class ProductReviewValidation
{
    protected $default = [
        'name' => ['label' => 'ユーザー名', 'rules' => ["required", "max_length[250]"]],
        'title' => ['label' => 'タイトル', 'rules' => ["required", "max_length[250]"]],
        'description' => ['label' => 'コメント', 'rules' => ["required", "max_length[500]"]],
        'status' => ['label' => 'ステータス', 'rules' => ["required", "in_list[pending,publish]"]],
        'product_id' => ['label' => '製品', 'rules' => ["required", "integer"]],
        'recommend_level' => ['label' => '評価', 'rules' => ["required", "integer","greater_than_equal_to[1]","less_than_equal_to[5]"]]
    ];

    public function getDefaultRule(ShopifySDK $shopifySDK)
    {
        $rule = $this->default;
        $rule["product_id"]["rules"][] = static function ($value, $data, &$error, $field) use ($shopifySDK) {
            try {
                $product = $shopifySDK->Product($value)->get();
            } catch (\Exception $e) {
                $error = '商品が存在しません';
                return false;
            }

            if (isset($product["published_scope"]) && $product["published_scope"] !== "global") {
                $error = '公開された商品のみ選択可能です。';
                return false;
            }

            return true;
        };

        return $rule;
    }
}
