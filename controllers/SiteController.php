<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\components\SiteHelper;
use app\components\LogTraffic;
use app\models\Pages;
use yii\web\NotFoundHttpException;
use app\components\ProductGridWidget;
use yii\caching\TagDependency;

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
                    'logout' => ['post']
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
        return $this->render('page', ['model' => Pages::findOne(['slug' => 'main'])]);
    }
    
    /**
     * Displays content pages
     *
     * @return string
     */
    public function actionPage($slug)
    {
        return $this->render('page', ['model' => Yii::$app->cache->getOrSet('page_' . $slug, function() use ($slug){
            if (!$model = Pages::findOne(['slug' => $slug])) {
                throw new NotFoundHttpException(Yii::t('app', 'Page not found.'));
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
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model
        ]);
    }
}