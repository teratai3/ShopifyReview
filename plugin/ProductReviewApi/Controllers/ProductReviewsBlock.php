<?php

namespace Plugin\ProductReviewApi\Controllers;

use CodeIgniter\API\ResponseTrait;
use Plugin\ShopifyAuth\Controllers\Api\ApiBaseController;
use Plugin\ProductReviewApi\Models\ProductReviewsModel;
use Config\Database;

class ProductReviewsBlock extends ApiBaseController
{
    use ResponseTrait;

    protected $ProductReviews;

    public function __construct()
    {
        parent::__construct();
        $this->ProductReviews = new ProductReviewsModel();
    }


    public function create()
    {
        $shopify = $this->getShopifySDK();

        try {
            $theme_id = $this->getMainThemeId($shopify);
            if ($theme_id) {
                // log_message("info",$theme_id);
                //$liquidContent = $this->getLiquidContent($shopify, $theme_id);
                //log_message('error',json_encode($liquidContent, JSON_PRETTY_PRINT));
                $newContent = $this->getNewBlockContent();
                $this->addNewLiquidFile($shopify, $theme_id, $newContent);
            } else {
                return $this->fail(["error" => 'メインテーマが見つかりません'], 404);
            }
        } catch (\Exception $e) {
            // log_message('error',$e->getMessage());
            return $this->fail(["error" => $e->getMessage()], 404);
        }

        return $this->respond(['message' => 'アプリブロックが正常に作成されました' . $theme_id], 200);
    }

    private function getMainThemeId($shopify)
    {
        $themes = $shopify->Theme->get();
        foreach ($themes as $theme) {
            if ($theme['role'] == 'main') {
                return $theme['id'];
            }
        }
        return null;
    }

    private function getLiquidContent($shopify, $theme_id)
    {
        $asset = $shopify->Theme($theme_id)->Asset->get([
            // 'asset[key]' => 'sections/custom-section.liquid',
            // 'asset[key]' => 'layout/password.liquid',
            'asset[key]' => 'sections/cl-review-block.liquid',
            'theme_id' => $theme_id,
        ]);
        return $asset;
    }

    private function appendLiquidBlock($liquidContent)
    {
        $newContent = $liquidContent . "\n{% for block in section.blocks %}\n";
        $newContent .= "  {% case block.type %}\n";
        $newContent .= "    {% when 'your-app-block-type' %}\n";
        $newContent .= "      <div>{{ block.settings.key }}</div>\n";
        $newContent .= "  {% endcase %}\n";
        $newContent .= "{% endfor %}\n";
        return $newContent;
    }

    private function getNewBlockContent()
    {
        // 新しいブロックの内容を定義します
        $newContent = "{% for block in section.blocks %}\n";
        $newContent .= "  {% case block.type %}\n";
        $newContent .= "    {% when 'your-app-block-type' %}\n";
        $newContent .= "      <div>{{ block.settings.key }}</div>\n";
        $newContent .= "  {% endcase %}\n";
        $newContent .= "{% endfor %}\n";
        return $newContent;
    }

    private function addNewLiquidFile($shopify, $theme_id, $newContent)
    {
        $shopify->Theme($theme_id)->Asset->put([
            'asset' => [
                'key' => 'sections/custom-section.liquid',
                'value' => $newContent,
            ],
        ]);
    }   
}
