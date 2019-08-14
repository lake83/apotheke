<?php

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

echo  \yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'number',
            'name',
            'phone',
            'address',
            [
                'attribute' => 'payment_id',
                'value' => $model->payment->name
            ],
            [
                'attribute' => 'delivery_id',
                'value' => $model->delivery->name
            ],
            'delivery_sum',
            [
                'attribute' => 'coupon_id',
                'format' => 'raw',
                'value' => $model->coupon_id ? ('<b>' . $model->couponData->code . '</b> ' . Yii::t('app', 'discount') . ' ' . $model->couponData->value .
                    ($model->couponData->type == \app\models\Coupon::TYPE_PERCENT ? ' %' : ' €')) : null
            ],
            [
                'attribute' => 'sum',
                'format' => 'raw',
                'value' => '<b>' . $model->sum . ' €</b>'
            ]
        ]
    ]);
?>