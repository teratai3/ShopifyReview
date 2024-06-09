<?php

namespace Plugin\ScriptAssetApi\Controllers;

use CodeIgniter\API\ResponseTrait;
use Plugin\ShopifyAuth\Controllers\Api\ApiBaseController;


class Assets extends ApiBaseController
{
    use ResponseTrait;

    public function review()
    {
        return $this->respond([
            "data" => view('Plugin\ScriptAssetApi\Views\Assets\review'),
        ]);
    }
}
