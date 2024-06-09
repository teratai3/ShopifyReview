<?php

namespace Plugin\ProductReviewApi\Models;

use CodeIgniter\Model;

class ProductReviewsModel extends Model
{
    protected $table = 'product_reviews';
    protected $primaryKey = 'id';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $useTimestamps = true;
    
    protected $allowedFields = ['shopify_auth_id','product_id','name','title', 'description','recommend_level', 'status'];
}
