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
 * @property double $free_sum_text
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
            [['name', 'free_sum_text'], 'string', 'max' => 255]
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
            'free_sum_text' => Yii::t('app', 'Free shipping text'),
            'is_active' => Yii::t('app', 'Active')
        ];
    }
    
    /**
     * List of all deliveries
     */
    public static function getAll()
    {
        return ArrayHelper::map(self::find()->select(['id', 'name', 'price', 'free_sum', 'free_sum_text'])->where(['is_active' => 1])
            ->asArray()->orderBy('name ASC')->all(), 'id', function($model) {
            $format = Yii::$app->formatter;
            return $model['name'] . ' - ' . $format->asCurrency($model['price']) .
                ($model['free_sum'] ? ' (' . $model['free_sum_text'] . ' ' . $format->asCurrency($model['free_sum']) . ')' : '');
        });
    }
}