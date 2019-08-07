<?php

namespace app\components;

use yii\base\Widget;
use app\models\ProductsPrice;
use yii\data\ActiveDataProvider;

class ProductGridWidget extends Widget
{
    /**
     * @var integer product number
     */
    public $number;

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('@app/views/widget/product', ['dataProvider' => new ActiveDataProvider([
            'query' => ProductsPrice::find()->select(['products_price.amount', 'products_price.price'])
                ->innerJoin('products', '`products_price`.`product_id` = `products`.`id` AND `number` IN(' .
                $this->number . ') AND `is_active`=1')
        ])]);
    }
}