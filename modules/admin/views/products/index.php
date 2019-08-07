<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\SiteHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
?>

<p><?= Html::a(Yii::t('app', 'Create product'), ['create'], ['class' => 'btn btn-success']) ?></p>

<?=  GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'export' => false,
    'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            'number',
            'name',
            SiteHelper::is_active($searchModel),

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{prices} {update} {delete}',
                'buttons' => [
                    'prices' => function ($url, $model, $key) {
                        return Html::a('<span class="fa fa-euro"></span>', $url, ['title' => Yii::t('app', 'Prices'), 'data-pjax' => 0]);
                    }
                ],
                'options' => ['width' => '70px']
            ]
        ]
    ]);
?>