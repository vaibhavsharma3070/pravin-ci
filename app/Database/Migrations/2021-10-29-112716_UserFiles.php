<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserFiles extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,                
            ],
            'deleted_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'datetime',
                'null' => true,
            ],
            'CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(id)',
            'created_at datetime default current_timestamp',
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('user_files');
    }

    public function down()
    {
        //
        $this->forge->dropTable('user_files');
    }
}
