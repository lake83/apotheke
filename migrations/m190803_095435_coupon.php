<?php

use yii\db\Migration;

/**
 * Class m190803_095435_coupon
 */
class m190803_095435_coupon extends Migration
{
    public function up()
    {
        $this->createTable('coupon', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'code' => $this->string()->notNull(),
            'type' => $this->boolean()->defaultValue(1)->comment('1-sum,2-percent'),
            'value' => $this->float()->notNull(),
            'date_from' => $this->integer()->notNull(),
            'date_to' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'is_active' => $this->boolean()->defaultValue(1)
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null);
    }

    public function down()
    {
        $this->dropTable('coupon');
    }
}