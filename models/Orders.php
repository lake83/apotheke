<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property int $product_id
 * @property double $sum
 * @property int $delivery_id
 * @property double $delivery_sum
 * @property int $payment_id
 * @property string $host
 * @property string $referrer
 * @property string $ip
 * @property string $agent
 * @property string $cookie_id
 * @property string $language
 * @property int $status 1-new,2-processing,3-closed,4-canceled
 * @property int $created_at
 * @property int $updated_at
 */
class Orders extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_CLOSED = 3;
    const STATUS_CANSELED = 4;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'address', 'product_id', 'sum', 'delivery_id', 'delivery_sum', 'payment_id'], 'required'],
            [['product_id', 'delivery_id', 'payment_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['sum', 'delivery_sum'], 'number'],
            [['name', 'address', 'referrer'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['host', 'agent'], 'string', 'max' => 100],
            [['ip', 'language'], 'string', 'max' => 30],
            [['cookie_id'], 'string', 'max' => 50],
            ['status', 'default', 'value' => self::STATUS_NEW]
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
            'phone' => Yii::t('app', 'Phone'),
            'address' => Yii::t('app', 'Address'),
            'product_id' => Yii::t('app', 'Product'),
            'sum' => Yii::t('app', 'Sum'),
            'delivery_id' => Yii::t('app', 'Delivery'),
            'delivery_sum' => Yii::t('app', 'Delivery Sum'),
            'payment_id' => Yii::t('app', 'Payment'),
            'host' => Yii::t('app', 'Host'),
            'referrer' => Yii::t('app', 'Referrer'),
            'ip' => 'IP',
            'agent' => Yii::t('app', 'Agent'),
            'cookie_id' => Yii::t('app', 'Cookie ID'),
            'language' => Yii::t('app', 'Language'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Updated'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $request = Yii::$app->request;
            
            $this->host = $request->absoluteUrl;
            $this->referrer = $request->referrer;
            $this->ip = $request->userIP;
            $this->agent = $request->userAgent;
            $this->cookie_id = $request->cookies['_csrf']->value;
            $this->language = implode(',', $request->acceptableLanguages);
        }
        return parent::beforeSave($insert);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Delivery::className(), ['id' => 'delivery_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
    }
    
    /**
     * Returns a list of order statuses or name
     * 
     * @param integer $key key in an array of names
     * @return mixed
     */
    public static function getStatuses($key = null)
    {
        $array = [
            self::STATUS_NEW => Yii::t('app', 'New'),
            self::STATUS_PROCESSING => Yii::t('app', 'Processing'),
            self::STATUS_CLOSED => Yii::t('app', 'Closed'),
            self::STATUS_CANSELED => Yii::t('app', 'Canceled')
        ];
        return is_null($key) ? $array : $array[$key];
    }
}