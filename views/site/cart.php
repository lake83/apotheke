<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Warenkorb');
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
           'header' => Yii::t('main', 'Produkt'),
           'footer' => Yii::t('main', 'Gesamtbetrag:'),
           'footerOptions' => [
               'colspan' => 2
           ]
        ],
        [
           'attribute' => 'quantity',
           'format' => 'raw',
           'header' => Yii::t('main', 'Menge'),
           'value' => function ($model, $index, $widget) {
               return ($model['quantity']>1 ? Html::a('<span class="glyphicon glyphicon-minus"></span> ', ['site/cart', 'id' => $model['id'], 'action' => 'minus'], [
                        'title' => Yii::t('main', 'subtrahieren'),
                        'data-method' => 'POST',
                        'data-pjax' => 1
                    ]) : '') . $model['quantity'] . Html::a(' <span class="glyphicon glyphicon-plus"></span>', ['site/cart', 'id' => $model['id'], 'action' => 'plus'], [
                        'title' => Yii::t('main', 'hinzufügen'),
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
           'header' => Yii::t('main', 'Preis'),
           'value' => function ($model, $index, $widget) {
               return $model['price'];
           },
           'footer' => (Yii::$app->session['cart']['sum']?:'0.00') . ' €',
           'footerOptions' => [
               'colspan' => 2
           ]
        ],
        
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{remove}',
            'buttons' => [
                'remove' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-remove"></span>', ['site/cart', 'id' => $model['id'], 'action' => 'remove'], [
                        'title' => Yii::t('main', 'Produkt entfernen'),
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
?>