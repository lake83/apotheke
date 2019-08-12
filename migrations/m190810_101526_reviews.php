<?php

use yii\db\Migration;

/**
 * Class m190810_101526_reviews
 */
class m190810_101526_reviews extends Migration
{
    public function up()
    {
        $this->createTable('reviews', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'ip' => $this->string(30)->notNull(),
            'text' => $this->text()->notNull(),
            'is_active' => $this->boolean()->defaultValue(1),
            'created_at' => $this->integer()->notNull()
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null);
    }

    public function down()
    {
        $this->dropTable('reviews');
    }
}
