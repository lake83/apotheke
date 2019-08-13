<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Contact;
use app\components\SiteHelper;
use app\components\LogTraffic;
use app\models\Pages;
use yii\web\NotFoundHttpException;
use app\components\ProductGridWidget;
use app\components\MainGridWidget;
use yii\caching\TagDependency;
use yii\web\Response;
use yii\data\ArrayDataProvider;
use app\models\Reviews;
use app\models\ReviewsSearch;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'buy' => ['post']
                ]
            ],
            'traffic' => [
                'class' => LogTraffic::className(),
                'exept' => ['error', 'captcha', 'admin', 'logout']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('page', ['model' => Yii::$app->cache->getOrSet('page_main', function(){
            if (!$model = Pages::findOne(['slug' => 'main'])) {
                throw new NotFoundHttpException(Yii::t('main', 'Page not found.'));
            }
            $model->content = str_replace('{{grid}}', MainGridWidget::widget(), $model->content);
            return $model;
        }, 0, new TagDependency(['tags' => 'pages']))]);
    }
    
    /**
     * Displays content pages
     *
     * @param string $slug page alias
     * @return string
     */
    public function actionPage($slug)
    {
        return $this->render('page', ['model' => Yii::$app->cache->getOrSet('page_' . $slug, function() use ($slug){
            if (!$model = Pages::findOne(['slug' => $slug])) {
                throw new NotFoundHttpException(Yii::t('main', 'Page not found.'));
            }
            $model->content = preg_replace_callback('/{{product (.*?)}}/', function ($matches) {
                return ProductGridWidget::widget(['number' => $matches[1]]);
            }, $model->content);
            return $model;
        }, 0, new TagDependency(['tags' => 'pages']))]);
    }

    /**
     * Login admin action.
     *
     * @return string
     */
    public function actionAdmin()
    {
        $this->layout = '@app/modules/admin/views/layouts/main-login';
        
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(SiteHelper::redirectByRole(Yii::$app->user->status));
        }
        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post()) && $status = $model->login()) {
            return $this->redirect(SiteHelper::redirectByRole($status));
        }
        return $this->render('@app/modules/admin/views/admin/login', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new Contact;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', ['model' => $model]);
    }
    
    /**
     * Add product to cart
     *
     * @param integer $id product ID
     * @return string
     */
    public function actionBuy($id)
    {
        $request = Yii::$app->request;
        
        if ($request->isAjax && ($price = $request->post('price')) && ($name = $request->post('name'))) {
            $session = Yii::$app->session;
            
            if (!$session->has('cart')) {
                $session->set('cart', ['sum' => '0.00', 'quantity' => 0, 'products' => []]);
            }
            $cart = $session->get('cart');
            $cart['products'][$id]['id'] = $id;
            $cart['products'][$id]['name'] = $name;
            $cart['products'][$id]['price'] = $price;
            $cart['products'][$id]['quantity']++;
             
            $cart['sum']+= $price;
            $cart['quantity']++;
            
            $session->set('cart', $cart);
            
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
        
        if ($request->isPost && ($id = $request->get('id')) && ($action = $request->get('action'))) {
            $session = Yii::$app->session;
            $cart = $session->get('cart');
            
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
            $session->set('cart', $cart);
        }
        return $this->render('cart', ['dataProvider' => new ArrayDataProvider([
            'allModels' => Yii::$app->session->get('cart')['products'],
            'pagination' => false
        ])]);
    }
    
    /**
     * Displays reviews page
     *
     * @return string
     */
    public function actionReviews()
    {
        $model = new Reviews;
        $searchModel = new ReviewsSearch;
        $request = Yii::$app->request;

        if ($request->isAjax && $model->load($request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load($request->post()) && $model->save()) {
            return $this->refresh();
        }
        return $this->render('reviews', [
            'model' => $model,
            'dataProvider' => $searchModel->search(['front' => true])
        ]);
    }
}