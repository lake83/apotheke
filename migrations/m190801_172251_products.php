<?php

use yii\db\Migration;

/**
 * Class m190801_172251_products
 */
class m190801_172251_products extends Migration
{
    public function up()
    {
        $this->createTable('products', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'number' => $this->string(20)->notNull(),
            'price' => $this->decimal(10,2)->notNull(),
            'is_active' => $this->boolean()->defaultValue(1)
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null);
    }

    public function down()
    {
        $this->dropTable('products');
    }
}