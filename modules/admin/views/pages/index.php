<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\SiteHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pages');
?>

<p>
    <?= Html::a(Yii::t('app', 'Create page'), ['create'], ['class' => 'btn btn-success']) ?>
    <?= Html::a('<i class="fa fa-info-circle"></i>', ['#'], ['id' => 'blocks-info-btn', 'class' => 'btn btn-info pull-right']) ?>
</p>
<div id="blocks-info" class="hidden">
   <h2><?= Yii::t('app', 'Page elements') ?></h2>
   <div>
       <p>{{grid}} - <?= Yii::t('app', 'Products blocks on main page.') ?></p>
       <p>{{product 10001,10002}} - <?= Yii::t('app', 'Products grid on product page. Contains comma-separated product numbers.') ?></p>
   </div>
</div>

<?=  GridView::widget([
    'layout' => '{items}{pager}',
    'dataProvider' => $dataProvider,
    'pjax' => true,
    'export' => false,
    'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'image',
                'format' => 'raw',
                'filter' => false,
                'value' => function ($model, $index, $widget) {
                    return Html::img(SiteHelper::resized_image($model->image, 120, 100), ['width' => 70]);
                }
            ],
            'name',
            'slug',
            'position',
            'is_product:boolean',
            SiteHelper::is_active($searchModel),

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'visibleButtons' => [
                    'delete' => function ($model, $key, $index) {
                        return $model->slug !== 'main';
                    }
                ],
                'options' => ['width' => '50px']
            ]
        ]
    ]);
?>