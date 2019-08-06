<?php

use yii\db\Migration;

/**
 * Class m190802_140919_traffic
 */
class m190802_140919_traffic extends Migration
{
    public function up()
    {
        $this->createTable('traffic', [
            'id' => $this->primaryKey(),
            'host' => $this->string(100)->notNull(),
            'referrer' => $this->string()->notNull(),
            'ip' => $this->string(30)->notNull(),
            'agent' => $this->string(100)->notNull(),
            'cookie_id' => $this->string(50)->notNull(),
            'language' => $this->string(30)->notNull(),
            'created_at' => $this->integer()->notNull()
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null);
    }

    public function down()
    {
        $this->dropTable('traffic');
    }
}