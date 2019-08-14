<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $page
 * @property int $is_active
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['image', 'page'], 'string'],
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
            'page' => Yii::t('app', 'Page'),
            'is_active' => Yii::t('app', 'Active')
        ];
    }
    
    /**
     * List of all payments
     */
    public static function getAll()
    {
        return ArrayHelper::map(self::find()->select(['id', 'name'])->where(['is_active' => 1])->asArray()->orderBy('name ASC')->all(), 'id', 'name');
    }
}