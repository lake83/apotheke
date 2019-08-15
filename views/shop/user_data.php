<?php

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

echo  \yii\widgets\DetailView::widget([
        'model' => $model,
        'attributes' => [
            'number',
            'name',
            'surname',
            'phone',
            'email',
            'street',
            'city',
            'region',
            'postcode',
            'comment:ntext',
            [
                'attribute' => 'payment_id',
                'value' => $model->payment->name
            ],
            [
                'attribute' => 'delivery_id',
                'value' => $model->delivery->name
            ],
            [
                'attribute' => 'delivery_sum',
                'format' => 'raw',
                'value' => '<b>' . $model->delivery_sum  . ' €</b>'
            ],
            [
                'attribute' => 'coupon_id',
                'format' => 'raw',
                'value' => $model->coupon_id ? ('<b>' . $model->couponData->code . '</b> ' . Yii::t('main', 'discount') . ' ' . $model->couponData->value .
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