<?php

namespace app\components;

use yii\base\Widget;
use app\models\Pages;
use yii\data\ActiveDataProvider;

class MainGridWidget extends Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('@app/views/widget/main', ['dataProvider' => new ActiveDataProvider([
            'query' => Pages::find()->select(['slug', 'name', 'image'])->where(['is_product' => 1, 'is_active' => 1])
                ->limit(\Yii::$app->params['products_main'])->orderBy('position ASC'),
            'pagination' => false
        ])]);
    }
}