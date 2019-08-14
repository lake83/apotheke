<?php

namespace app\controllers;

use Yii;
use yii\web\{Controller, NotFoundHttpException};
use app\models\{Products, Orders, Payment};
use yii\data\ArrayDataProvider;
use yii\caching\TagDependency;
use yii\helpers\Json;

class ShopController extends Controller
{
    private $session;
    
    public function init()
    {
        $this->session = Yii::$app->session;
        
        if (!$this->session->has('cart')) {
            $this->session->set('cart', ['sum' => '0.00', 'quantity' => 0, 'products' => []]);
        }
        parent::init();
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
                    $cart['sum'] = '0.00';
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
            return $this->render('page_order', ['content' => Yii::$app->cache->getOrSet('page_order_' . $model->payment_id, function() use ($model){
                $page = str_replace('{{user_data}}', $this->renderPartial('user_data', ['model' => $model]), $model->payment->page);
                $page = str_replace('{{order}}', $this->renderPartial('order_data', [
                    'dataProvider' => new ArrayDataProvider([
                        'allModels' => Json::decode($model->products),
                        'pagination' => false
                    ])
                ]), $page);
                return $page;
            }, 0, new TagDependency(['tags' => 'order']))]);
        }
        return $this->render('order', [
            'model' => $model,
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $cart['products'],
                'pagination' => false
            ])
        ]);
    }
}