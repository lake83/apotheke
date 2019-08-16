<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = Yii::t('main', 'Shopping cart');

$this->registerJs("
$('#cart').on('pjax:success', function() {
    $.pjax.reload({container: '#menu-cart'});
});");
$format = Yii::$app->formatter;
?>

<h1><?= $this->title ?></h1>

<?php Pjax::begin(['id' => 'cart']);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items}',
    'showFooter' => true,
    'footerRowOptions' => [
        'style' => 'font-weight:bold;'
    ],
    'columns' => [
        [
           'attribute' => 'name',
           'header' => Yii::t('main', 'Product'),
           'footer' => Yii::t('main', 'Total amount:'),
           'footerOptions' => [
               'colspan' => 2
           ]
        ],
        [
           'attribute' => 'quantity',
           'format' => 'raw',
           'header' => Yii::t('main', 'Amount'),
           'value' => function ($model, $index, $widget) {
               return ($model['quantity']>1 ? Html::a('<span class="glyphicon glyphicon-minus"></span> ', ['shop/cart', 'id' => $model['id'], 'action' => 'minus'], [
                        'title' => Yii::t('main', 'subtract'),
                        'data-method' => 'POST',
                        'data-pjax' => 1
                    ]) : '') . $model['quantity'] . Html::a(' <span class="glyphicon glyphicon-plus"></span>', ['shop/cart', 'id' => $model['id'], 'action' => 'plus'], [
                        'title' => Yii::t('main', 'add'),
                        'data-method' => 'POST',
                        'data-pjax' => 1
                    ]);
           },
           'contentOptions' => [
               'style' => ['text-aligen' => 'center']
           ],
           'footerOptions' => [
               'class' => 'hidden'
           ]
        ],
        [
           'attribute' => 'price',
           'header' => Yii::t('main', 'Price'),
           'value' => function ($model, $index, $widget) use ($format) {
               return $format->asCurrency($model['price']);
           },
           'footer' => ($format->asCurrency(Yii::$app->session['cart']['sum']?:0)),
           'footerOptions' => [
               'colspan' => 2
           ]
        ],
        
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{remove}',
            'buttons' => [
                'remove' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['shop/cart', 'id' => $model['id'], 'action' => 'remove'], [
                        'title' => Yii::t('main', 'Remove product'),
                        'data-method' => 'POST',
                        'data-pjax' => 1
                    ]);
                }
            ],
            'options' => ['width' => '30px'],
            'footerOptions' => [
                'class' => 'hidden'
            ]
        ]
    ]
]);
Pjax::end();

if (Yii::$app->session->get('cart')['quantity'] > 0) {
    echo Html::a(Yii::t('main', 'To order'), ['shop/order'], ['class' => 'btn btn-primary pull-right']);
}
?>