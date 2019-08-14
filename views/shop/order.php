<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */
/* @var $model app\models\Orders */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Delivery;
use app\models\Payment;

$this->title = Yii::t('main', 'Order');
?>

<h1><?= $this->title ?></h1>

<?= GridView::widget([
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
           'value' => function ($model, $index, $widget) {
               return $model['price'] . ' €';
           },
           'footer' => Yii::$app->session['cart']['sum'] . ' €',
           'footerOptions' => [
               'colspan' => 2
           ]
        ]
    ]
]) ?>

<h3><?= Yii::t('main', 'Checkout') ?></h3>

<?php $form = ActiveForm::begin(['id' => 'add-review-form', 'layout' => 'horizontal']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), ['mask' => Yii::$app->params['phone_mask']]) ?>

<?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'coupon', ['enableAjaxValidation' => true])->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'delivery_id')->radioList(Delivery::getAll()) ?>

<?= $form->field($model, 'payment_id')->radioList(Payment::getAll()) ?>

<?= Html::submitButton(Yii::t('main', 'Complete order'), ['class' => 'btn btn-primary col-md-offset-3 mb-5']) ?>

<?php ActiveForm::end(); ?>