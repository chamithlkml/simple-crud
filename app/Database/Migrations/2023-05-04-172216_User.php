<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class User extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'firstname' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => false,
                'default' => null,
            ],
            'lastname' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
                'null' => false,
                'default' => null,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
                'default' => null,
            ],
            'mobile' => [
                'type' => 'INT',
                'constraint' => '16',
                'null' => false,
                'default' => null,
            ],
            'userename' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
                'null' => false,
                'default' => null,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
                'default' => null,
            ],
            'salt' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
                'default' => null,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['admin', 'user'],
                'null' => false,
                'default' => null,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id');
        $this->forge->addKey('username', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
