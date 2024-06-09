<?php

namespace Plugin\ProductReviewApi\Controllers\Front;

use CodeIgniter\API\ResponseTrait;
// use Plugin\ShopifyAuth\Controllers\Api\ApiBaseController;
// use CodeIgniter\RESTful\ResourceController;
use Plugin\ShopifyAuth\Controllers\Api\ApiFrontBaseController;
use Plugin\ProductReviewApi\Models\ProductReviewsModel;
use Config\Database;

class ProductReviews extends ApiFrontBaseController
{
    use ResponseTrait;

    protected $ProductReviews;

    public function __construct()
    {
        parent::__construct();
        $this->ProductReviews = new ProductReviewsModel();
    }

    public function index()
    {

        $page = $this->request->getVar('page') ?? 1;
        $perPage = $this->request->getVar('perPage') ?? 10;
        $title = $this->request->getVar('title') ?? "";
       
        $product_id = $this->request->getVar('product_id') ?? "";

        // クエリを初期化
        $query = $this->ProductReviews->orderBy("created_at", "DESC");


        $query->where('shopify_auth_id', $this->getShopId());
        // 検索語が提供されている場合はLIKE条件を追加
        if (!empty($title)) {
            $query->like('title', $title);
        }

        $query->where('status', "publish");

        if (!empty($product_id)) {
            $query->where('product_id', $product_id);
        }

        $totalItems = $this->ProductReviews->countAllResults(false);
        $totalPages = ceil($totalItems / $perPage);

        $datas = $query->paginate($perPage, '', $page);

        return $this->respond([
            "data" => $datas,
            "meta" => [
                "currentPage" => $page,
                "perPage" => $perPage,
                "totalItems" => $totalItems,
                "totalPages" => $totalPages,
                "hasNextPage" => $page < $totalPages,
                "hasPreviousPage" => $page > 1,
            ],
        ]);
    }

   
    public function create()
    {
        $jsonData = $this->request->getJSON(true);


        if (is_null($jsonData) || !is_array($jsonData)) {
            return $this->failValidationErrors(['error' => '無効なJSONデータ']);
        }

        
        $jsonData["status"] = "pending";

        // バリデーションを実行
        if (!$this->validateData($jsonData, config("ProductReviewValidation")->getDefaultRule($this->getShopifySDK()))) {
            //log_message('info', 'Received data: ' . json_encode($this->validator->getErrors()));
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $db = Database::connect();
        $db->transStart();

        try {
            $jsonData["shopify_auth_id"] = $this->getShopId();
            $result = $this->ProductReviews->insert($jsonData);

            if ($result === false) {
                throw new \Exception($db->error()["message"]);
            }

            $db->transCommit();
        } catch (\Exception $e) {
            $db->transRollback();
            // log_message('error', 'サーバーエラー：' . $e->getMessage());
            return $this->failServerError('サーバーエラー：' . $e->getMessage());
        }

        return $this->respondCreated([
            "id" => $result,
            'message' => '商品レビューの追加に成功しました。'
        ]);
    }

   
}
