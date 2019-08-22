<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */
/* @var $model app\models\Orders */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Delivery;
use app\models\Payment;
use yii\helpers\Url;

$this->title = Yii::t('main', 'Order');

$format = Yii::$app->formatter;
?>

<h1><?= $this->title ?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table cart-table'],
    'layout' => '{items}',
    'showFooter' => true,
    'footerRowOptions' => [
        'style' => 'font-weight:bold;'
    ],
    'columns' => [
        [
           'attribute' => 'name',
           'header' => Yii::t('main', 'Product'),
           'footer' => '<b>' . Yii::t('main', 'Coupon') . ':</b><hr/><b>' . Yii::t('main', 'Delivery') . ':</b><hr/><b>' . Yii::t('main', 'Total amount') . ':</b>',
           'footerOptions' => [
               'colspan' => 2,
               'style' => ['padding' => '12px 0']
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
           'value' => function ($model, $index, $widget) use ($format) {
               return $format->asCurrency($model['price']);
           },
           'footer' => '<span>- ' . $format->asCurrency(0) . '</span><hr/><span>' . $format->asCurrency(0) . '</span><hr/><span>' . $format->asCurrency(Yii::$app->session['cart']['sum']) . '</span>',
           'footerOptions' => [
               'class' => 'total',
               'colspan' => 2,
               'style' => ['padding' => '12px 0']
           ]
        ]
    ]
]) ?>


<h3><?= Yii::t('main', 'Checkout') ?></h3>

<?php $form = ActiveForm::begin(['id' => 'add-review-form']); ?>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-7">
            <?= $form->field($model, 'coupon', ['enableAjaxValidation' => true])->textInput(['maxlength' => true]) ?>
            
            <?= Html::activeHiddenInput($model, 'coupon_id') ?>
        </div>
        <div class="col-md-3">
            <?= Html::a(Yii::t('main', 'Redeem'), ['#'], ['class' => 'btn btn-primary add_coupon', 'onclick' => 'js:return false;']) ?>
        </div>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'delivery_id')->radioList(Delivery::getAll()) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'payment_id')->radioList(Payment::getAll()) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), ['mask' => Yii::$app->params['phone_mask']]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'region')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'postcode')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>
    </div>
</div>

<?= Html::submitButton(Yii::t('main', 'Complete order'), ['class' => 'btn btn-primary col-md-offset-3 mb-5']) ?>

<?php ActiveForm::end();

$this->registerJs("
$(document).on('click change', '.add_coupon, #orders-coupon, #orders-delivery_id input[name=\"Orders[delivery_id]\"]', function () {
    $.post('" . Url::to(['shop/count-total']) . "', {
        number: $('#orders-coupon').val(),
        delivery_id: $('#orders-delivery_id input[name=\"Orders[delivery_id]\"]:checked').val()
    }).done(function(data) {
        $('a.cart strong').text(data.sum);
        $('td.total').find('span').eq(0).text('- ' + data.discount);
        $('td.total').find('span').eq(1).text(data.delivery_sum);
        $('td.total').find('span').eq(2).text(data.sum);
        $('#orders-coupon_id').val(data.coupon_id); 
    });
});"); ?>