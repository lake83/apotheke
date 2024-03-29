<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\caching\TagDependency;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property string $number
 * @property double $price
 * @property int $is_active
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price'], 'required'],
            ['is_active', 'integer'],
            ['number', 'string', 'max' => 20],
            ['price', 'number'],
            ['name', 'string', 'max' => 255]
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
            'number' => Yii::t('app', 'Number'),
            'price' => Yii::t('app', 'Price') . ', EUR',
            'is_active' => Yii::t('app', 'Active')
        ];
    }
    
    /**
     * List of all products
     */
    public static function getAll()
    {
        return ArrayHelper::map(self::find()->select(['id', 'name'])->where(['is_active' => 1])->asArray()->orderBy('name ASC')->all(), 'id', 'name');
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        TagDependency::invalidate(Yii::$app->cache, 'pages');
        
        return parent::afterSave($insert, $changedAttributes);
    }
    
    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        TagDependency::invalidate(Yii::$app->cache, 'pages');
        
        parent::afterDelete();
    }
}