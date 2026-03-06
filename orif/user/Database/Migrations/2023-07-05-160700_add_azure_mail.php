<?php

namespace User\Database\Migrations;

class AddAzureMail extends \CodeIgniter\Database\Migration
{
    public function up()
    {
        // Check if the column already exists with a direct SQL query
        $db = \Config\Database::connect();
        $query = $db->query("SHOW COLUMNS FROM `user` LIKE 'azure_mail'");
        $columnExists = $query->getNumRows() > 0;

        if (!$columnExists) {
            $fields = [
                'azure_mail' =>[
                    'type'              => 'VARCHAR',
                    'constraint'        => '100',
                    'null'              => true,
                    'default'           => null,
                    
                    // Where to place the field
                    'after'             => 'email',
                ],
            ];
            $this->forge->addColumn('user', $fields);
        }
    }

    /**
     * @inheritDoc
     */
    public function down()
    {
        // $this->forge->dropTable('user');
        $this->forge->dropColumn('user', 'azure_mail'); // to drop one single column
    }
}