<?php

namespace app\controllers;

use Yii;
use yii\web\{Controller, NotFoundHttpException};
use app\models\{Products, Orders, Payment, Coupon, Delivery};
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\filters\VerbFilter;

class ShopController extends Controller
{
    private $session;
    
    public function init()
    {
        $this->session = Yii::$app->session;
        
        if (!$this->session->has('cart')) {
            $this->session->set('cart', ['sum' => 0, 'quantity' => 0, 'products' => []]);
        }
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'buy' => ['post']
                ]
            ]
        ];
    }
    
    /**
     * Add product to cart
     *
     * @param integer $id product ID
     * @return string
     */
    public function actionBuy($id)
    {
        if ($product = Products::findOne($id)) {
            $cart = $this->session->get('cart');
            $cart['products'][$id]['id'] = $id;
            $cart['products'][$id]['name'] = $product->name;
            $cart['products'][$id]['price'] = $product->price;
            $cart['products'][$id]['quantity']++;
             
            $cart['sum']+= $product->price;
            $cart['quantity']++;
            
            $this->session->set('cart', $cart);
            
            return $this->redirect(['cart']);
        }
    }
    
    /**
     * Displays shopping cart
     *
     * @return string
     */
    public function actionCart()
    {
        $request = Yii::$app->request;
        $cart = $this->session->get('cart');
        
        if ($request->isPost && ($id = $request->get('id')) && ($action = $request->get('action'))) {
            if ($action == 'plus') {
                $cart['products'][$id]['quantity']++;
                $cart['sum']+= $cart['products'][$id]['price'];
                $cart['quantity']++;
            } elseif ($action == 'minus') {
                $cart['products'][$id]['quantity']--;
                $cart['sum']-= $cart['products'][$id]['price'];
                $cart['quantity']--;
            } else {
                $cart['quantity']-= $cart['products'][$id]['quantity'];
                
                if ($cart['quantity'] == 0) {
                    $cart['sum'] = 0;
                } else {
                    $cart['sum']-= $cart['products'][$id]['price'] * $cart['products'][$id]['quantity'];
                }
                unset($cart['products'][$id]);
            }
            $this->session->set('cart', $cart);
        }
        return $this->render('cart', [
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $cart['products'],
                'pagination' => false
            ])
        ]);
    }
    
    /**
     * Displays order page
     *
     * @return string
     */
    public function actionOrder()
    {
        $cart = $this->session->get('cart');
        
        if ($cart['quantity'] == 0) {
            return $this->redirect(['cart']);
        }
        $model = new Orders;
        $request = Yii::$app->request;

        if ($request->isAjax && $model->load($request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load($request->post()) && $model->save()) {
            $content = str_replace('{{user_data}}', $this->renderPartial('user_data', ['model' => $model]), $model->payment->page);
            $content = str_replace('{{order_number}}', $model->number, $content);
            $content = str_replace('{{order}}', $this->renderPartial('order_data', [
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => Json::decode($model->products),
                    'pagination' => false
                ])
            ]), $content);
            return $this->render('page_order', ['content' => $content]);
        }
        return $this->render('order', [
            'model' => $model,
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $cart['products'],
                'pagination' => false
            ])
        ]);
    }
    
    /**
     * Use coupon and delivery on order page
     *
     * @return string
     */
    public function actionCountTotal()
    {
        $request = Yii::$app->request;
        $format = Yii::$app->formatter;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if ($request->isAjax) {
            $cart = $this->session->get('cart');
            $sum = $cart['sum'];
            
            if (($number = $request->post('number')) && ($coupon = Coupon::find()->select(['id', 'type', 'value'])->where(['code' => $number])->asArray()->one())) {
                if ($coupon['type'] == Coupon::TYPE_SUM) {
                    $sum-= $coupon['value'];
                } else {
                    $discount = ($coupon['value']*$sum)/100;
                    $sum-= $discount;
                }
            }
            if (($delivery_id = $request->post('delivery_id')) && ($delivery = Delivery::find()->select(['price', 'free_sum'])->where(['id' => $delivery_id])->asArray()->one())) {
                $delivery_sum = $delivery['free_sum'] && $delivery['free_sum'] < $sum ? 0 : $delivery['price'];
                $sum+= $delivery_sum;
            }
            return [
                'status' => 'success',
                'coupon_id' => $number && $coupon ? $coupon['id'] : '',
                'discount' => $format->asCurrency(($number && $coupon ? ($coupon['type'] == Coupon::TYPE_SUM ? $coupon['value'] : $discount) : 0)),
                'delivery_sum' => $format->asCurrency(($delivery_id && $delivery ? $delivery_sum : 0)),
                'sum' => $format->asCurrency($sum)
            ];
        }
    }
}