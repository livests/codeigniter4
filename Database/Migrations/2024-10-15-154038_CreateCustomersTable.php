<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "id" =>
            [
                "type" => "INT",
                "unsigned" => true,
                "auto_increment" => true,
            ],
                "name"=>[
                    "type" => "VARCHAR",
                    "constraint" => 255,
                    "null" => false
                ],
                "email"=>[
                    "type" => "VARCHAR",
                    "constraint" => 255,
                    "null" => false
                ],
                "mobile_number"=>[
                    "type" => "VARCHAR",
                    "constraint" => 255,
                    "null" => true
                ],
                "address"=>[
                    "type" => "TEXT",
                    "null" => false
                ],
                "image_url"=>[
                    "type" => "VARCHAR",
                    "constraint" => 255,
                    "null" => true
                ],
                "ip_address"=>[
                    "type" => "VARCHAR",
                    "constraint" => 255,
                    "null" => false
                ],
                "created_at datetime default current_timestamp",
                "updated_at datetime default current_timestamp",
                "status" =>
                [
                    "type" => "INT",
                    "constraint" => 1,
                    "default" => 1
                ]

                ]);

                $this->forge->addPrimaryKey('id');
                $this->forge->createTable('customer_info');
    }

    public function down()
    {
        $this->forge->dropTable('customer_info');
    }
}
