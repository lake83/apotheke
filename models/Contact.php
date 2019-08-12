<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "contact".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $text
 * @property int $created_at
 */
class Contact extends \yii\db\ActiveRecord
{
    public $verifyCode;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contact';
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
            [['name', 'email', 'subject', 'text'], 'required'],
            [['text'], 'string'],
            [['created_at'], 'integer'],
            [['name', 'subject'], 'string', 'max' => 255],
            ['email', 'email'],
            [['email'], 'string', 'max' => 100],
            ['verifyCode', 'captcha'],
            [['name', 'subject', 'text'], 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }]
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
            'subject' => Yii::t('main', 'Subject'),
            'text' => Yii::t('main', 'Text'),
            'created_at' => Yii::t('main', 'Created'),
            'verifyCode' => Yii::t('main', 'Verification code')
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->mailer->compose()
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->text)
            ->send();
                
        return parent::afterSave($insert, $changedAttributes);
    }
}