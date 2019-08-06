<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "coupon".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $type 1-sum,2-percent
 * @property double $value
 * @property int $date_from
 * @property int $date_to
 * @property int $created_at
 * @property int $is_active
 */
class Coupon extends \yii\db\ActiveRecord
{
    const TYPE_SUM = 1;
    const TYPE_PERCENT = 2;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coupon';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp'  => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'code', 'type', 'value', 'date_from', 'date_to'], 'required'],
            [['type', 'created_at', 'is_active'], 'integer'],
            [['value'], 'number'],
            [['name'], 'string', 'max' => 255],
            ['date_from', 'date', 'format' => 'php:d.m.Y', 'timestampAttribute' => 'date_from'],
            ['date_to', 'date', 'format' => 'php:d.m.Y', 'timestampAttribute' => 'date_to']
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
            'code' => Yii::t('app', 'Code'),
            'type' => Yii::t('app', 'Type'),
            'value' => Yii::t('app', 'Value'),
            'date_from' => Yii::t('app', 'Date From'),
            'date_to' => Yii::t('app', 'Date To'),
            'created_at' => Yii::t('app', 'Created'),
            'is_active' => Yii::t('app', 'Active')
        ];
    }
    
    /**
     * Returns a list of coupon types or name
     * 
     * @param integer $key key in an array of names
     * @return mixed
     */
    public static function getTypes($key = null)
    {
        $array = [
            self::TYPE_SUM => Yii::t('app', 'Sum'),
            self::TYPE_PERCENT => Yii::t('app', 'Percent')
        ];
        return is_null($key) ? $array : $array[$key];
    }
}