<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "delivery".
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property double $price
 * @property double $free_sum
 * @property int $is_active
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['image'], 'string'],
            [['price', 'free_sum'], 'number'],
            [['price', 'free_sum'], 'default', 'value' => 0],
            [['is_active'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'Name'),
            'image' => Yii::t('app', 'Image'),
            'price' => Yii::t('app', 'Price'),
            'free_sum' => Yii::t('app', 'Free shipping sum'),
            'is_active' => Yii::t('app', 'Active')
        ];
    }
    
    /**
     * List of all deliveries
     */
    public static function getAll()
    {
        return ArrayHelper::map(self::find()->select(['id', 'name', 'price', 'free_sum'])->where(['is_active' => 1])
            ->asArray()->orderBy('name ASC')->all(), 'id', function($model) {
            return $model['name'] . ' - ' . $model['price'] . ' €' .
                ($model['free_sum'] ? ' (' . Yii::t('main', 'Free shipping on order amount') . ': ' . $model['free_sum'] . ' €)' : '');
        });
    }
}