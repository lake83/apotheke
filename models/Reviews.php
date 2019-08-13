<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "reviews".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $ip
 * @property string $text
 * @property int $is_active
 * @property int $created_at
 */
class Reviews extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reviews';
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
            [['name', 'email', 'text'], 'required'],
            ['text', 'string'],
            ['email', 'email'],
            [['is_active', 'created_at'], 'integer'],
            ['name', 'string', 'max' => 255],
            ['email', 'string', 'max' => 100],
            ['ip', 'string', 'max' => 30],
            ['ip', 'unique', 'message' => Yii::t('main', 'You have already left a review.')],
            [['name', 'text'], 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
            ['is_active', 'default', 'value' => 0]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('main', 'Name'),
            'email' => 'Email',
            'ip' => 'IP',
            'text' => Yii::t('main', 'Text'),
            'is_active' => Yii::t('app', 'Active'),
            'created_at' => Yii::t('app', 'Created')
        ];
    }
}