<?php

use yii\db\Migration;

/**
 * Class m190802_105313_payment_delivery
 */
class m190802_105313_payment_delivery extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('payment', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'image' => $this->text()->notNull(),
            'page' => $this->text()->notNull(),
            'is_active' => $this->boolean()->defaultValue(1)
        ], $tableOptions);
        
        $this->createTable('delivery', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'image' => $this->text()->notNull(),
            'price' => $this->float()->notNull(),
            'free_sum' => $this->float()->notNull(),
            'is_active' => $this->boolean()->defaultValue(1)
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('payment');
        $this->dropTable('delivery');
    }
}