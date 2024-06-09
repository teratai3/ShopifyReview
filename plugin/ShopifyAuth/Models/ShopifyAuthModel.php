<?php

namespace Plugin\ShopifyAuth\Models;

use CodeIgniter\Model;

class ShopifyAuthModel extends Model
{
    protected $table = 'shopify_auth';
    protected $primaryKey = 'id';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $useTimestamps = true;
    protected $allowedFields = ['shop_domain','access_token'];
}