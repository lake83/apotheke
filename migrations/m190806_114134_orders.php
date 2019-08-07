<?php

use yii\db\Migration;

/**
 * Class m190806_114134_orders
 */
class m190806_114134_orders extends Migration
{
    public function up()
    {
        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'phone' => $this->string(20)->notNull(),
            'address' => $this->string()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'sum' => $this->float()->notNull(),
            'delivery_id' => $this->integer()->notNull(),
            'delivery_sum' => $this->float()->notNull(),
            'payment_id' => $this->integer()->notNull(),
            'host' => $this->string(100)->notNull(),
            'referrer' => $this->string()->notNull(),
            'ip' => $this->string(30)->notNull(),
            'agent' => $this->string(100)->notNull(),
            'cookie_id' => $this->string(50)->notNull(),
            'language' => $this->string(30)->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(1)->comment('1-new,2-processing,3-closed,4-canceled'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null);
    }

    public function down()
    {
        $this->dropTable('orders');
    }
}