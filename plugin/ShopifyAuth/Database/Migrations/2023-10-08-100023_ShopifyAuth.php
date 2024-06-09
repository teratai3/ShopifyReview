<?php

namespace Plugin\ShopifyAuth\Database\Migrations;

use CodeIgniter\Database\Migration;

class ShopifyAuth extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'shop_domain' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => false,
                'unique' => true
            ],
            'access_token' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('shopify_auth');
    }

    public function down()
    {
        $this->forge->dropTable('shopify_auth');
    }
}
