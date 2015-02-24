<?php

use yii\db\Schema;
use yii\db\Migration;

class m150223_210553_initial extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id'                   => Schema::TYPE_PK,
            'auth_key'             => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash'        => Schema::TYPE_STRING . ' NOT NULL',
            'email'                => Schema::TYPE_STRING . ' NOT NULL',
            'activate_token'       => Schema::TYPE_STRING . '(32) NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%user_contact}}', [
            'id'                   => Schema::TYPE_PK,
            'user_id'              => Schema::TYPE_INTEGER . ' NOT NULL',
            'name'                 => Schema::TYPE_STRING . '(64) NOT NULL',
            'phone'                => Schema::TYPE_STRING . ' NOT NULL',
            'email'                => Schema::TYPE_STRING . ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user_contact}}');
        $this->dropTable('{{%user}}');
    }
}
