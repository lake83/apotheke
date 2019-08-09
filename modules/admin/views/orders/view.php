<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Coupon;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

$this->title = $model->name;

?>
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'phone',
            'address',
            [
                'attribute' => 'product_id',
                'value' => $model->product->name
            ],
            'sum',
            [
                'attribute' => 'coupon_id',
                'value' => $model->coupon->code . ' ' . Yii::t('app', 'discount') . ' ' . $model->coupon->value .
                    $model->coupon->type == Coupon::TYPE_PERCENT ? ' %' : ' €'
            ],
            [
                'attribute' => 'delivery_id',
                'value' => $model->delivery->name
            ],
            'delivery_sum',
            [
                'attribute' => 'payment_id',
                'value' => $model->payment->name
            ],
            [
                'attribute' => 'status',
                'value' => $model->getStatuses($model->status)
            ],
            'created_at:datetime',
            'updated_at:datetime',
            'host',
            'referrer',
            'ip',
            'agent',
            'cookie_id',
            'language'
        ]
    ]) ?>