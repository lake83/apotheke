<?php

namespace app\components;

use yii\base\Widget;
use app\models\ProductsPrice;
use yii\data\ActiveDataProvider;

class ProductGridWidget extends Widget
{
    /**
     * @var integer product ID
     */
    public $product_id;

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('@app/views/widget/product', ['dataProvider' => new ActiveDataProvider([
            'query' => ProductsPrice::find()->where(['product_id' => $this->product_id])
        ])]);
    }
}