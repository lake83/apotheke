<?php

use yii\db\Migration;

/**
 * Class m190801_172251_products
 */
class m190801_172251_products extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('products', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'number' => $this->string(20)->notNull(),
            'is_active' => $this->boolean()->defaultValue(1)
        ], $tableOptions);
        
        $this->createTable('products_price', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'price' => $this->float()->notNull()
        ], $tableOptions);
        
        $this->createIndex('idx-products_price_id', 'products_price', 'product_id');
        $this->addForeignKey('products_price_ibfk_1', 'products_price', 'product_id', 'products', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('products_price_ibfk_1', 'products_price');
        $this->dropIndex('idx-products_price_id', 'products_price');
        
        $this->dropTable('products');
        $this->dropTable('products_price');
    }
}