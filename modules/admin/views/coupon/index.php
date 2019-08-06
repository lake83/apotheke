<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\SiteHelper;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CouponSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Coupon');
?>

<p><?= Html::a(Yii::t('app', 'Create coupon'), ['create'], ['class' => 'btn btn-success']) ?></p>

<?=  GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'export' => false,
    'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            'name',
            'code',
            [
                'attribute' => 'type',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'type',
                    $searchModel->getTypes(),
                    ['class' => 'form-control', 'prompt' => Yii::t('app', '- choose -')]
                ),
                'value' => function ($model, $index, $widget) {
                    return $model->getTypes($model->type);}
            ],
            [
                'attribute' => 'date_from',
                'format' => ['date', 'php:j M Y'],
                'filter' => DatePicker::widget([
                    'model'=>$searchModel,
                    'options' => ['class' => 'form-control'],               
                    'attribute' => 'date_from',
                    'dateFormat' => 'dd.MM.yyyy'
                ])
            ],
            [
                'attribute' => 'date_to',
                'format' => ['date', 'php:j M Y'],
                'filter' => DatePicker::widget([
                    'model'=>$searchModel,
                    'options' => ['class' => 'form-control'],               
                    'attribute' => 'date_to',
                    'dateFormat' => 'dd.MM.yyyy'
                ])
            ],
            SiteHelper::is_active($searchModel),
            SiteHelper::created_at($searchModel),

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'options' => ['width' => '50px']
            ]
        ]
    ]);
?>