<?php

namespace app\components;

use yii\base\Widget;
use app\models\Products;
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
            'query' => Products::find()->where('`number` IN(' . $this->number . ')')->andWhere(['is_active' => 1])
        ])]);
    }
}