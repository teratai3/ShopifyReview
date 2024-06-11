<?php

namespace Plugin\ShopifyAuth\Models;

use CodeIgniter\Model;
use Config\Services;

class ShopifyAuthModel extends Model
{
    protected $table = 'shopify_auth';
    protected $primaryKey = 'id';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $useTimestamps = true;
    protected $allowedFields = ['shop_domain','access_token'];

    protected $beforeInsert = ['encryptToken'];
    protected $beforeUpdate = ['encryptToken'];
    protected $afterFind = ['decryptToken'];
 
    protected function encryptToken(array $data)
    {
        //アクセストークンを常に暗号化
        if (isset($data['data']['access_token'])) {
            $encrypter = Services::encrypter();
            $data['data']['access_token'] = base64_encode($encrypter->encrypt($data['data']['access_token']));
        }
        return $data;
    }

    protected function decryptToken(array $data)
    {
        //findした時に復号化
        if (isset($data['data']['access_token'])) {
            $encrypter = Services::encrypter();
            $data['data']['access_token'] = $encrypter->decrypt(base64_decode($data['data']['access_token']));
        }

        return $data;
    }

}