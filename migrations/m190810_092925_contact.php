<?php

use yii\db\Migration;

/**
 * Class m190810_092925_contact
 */
class m190810_092925_contact extends Migration
{
    public function up()
    {
        $this->createTable('contact', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string(100)->notNull(),
            'subject' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull()
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null);
    }

    public function down()
    {
        $this->dropTable('contact');
    }
}