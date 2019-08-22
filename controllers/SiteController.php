<?php

namespace app\controllers;

use Yii;
use yii\filters\{AccessControl, VerbFilter};
use app\models\{LoginForm, Contact, Reviews, Pages, Products};
use app\components\{SiteHelper, LogTraffic, ProductGridWidget, MainGridWidget};
use yii\web\{NotFoundHttpException, Response, Controller};
use yii\caching\TagDependency;
use yii\data\ActiveDataProvider;

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
        return $this->render('page', ['model' => Yii::$app->cache->getOrSet('page_main', function(){
            if (!$model = Pages::findOne(['slug' => 'main'])) {
                throw new NotFoundHttpException(Yii::t('main', 'Page not found.'));
            }
            $model->content = str_replace('{{grid}}', $this->renderPartial('main', [
                'dataProvider' => new ActiveDataProvider([
                    'query' => Pages::find()->select(['slug', 'name', 'image'])->where(['is_product' => 1, 'is_active' => 1])
                        ->limit(Yii::$app->params['products_main'])->orderBy('position ASC'),
                    'pagination' => false
                ])
            ]), $model->content);
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
                return $this->renderPartial('product', [
                    'dataProvider' => new ActiveDataProvider([
                        'query' => Products::find()->where('`number` IN(' . $matches[1] . ')')->andWhere(['is_active' => 1])
                    ])
                ]);
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
        if (!$page = Pages::findOne(['slug' => 'contact'])) {
            throw new NotFoundHttpException(Yii::t('main', 'Page not found.'));
        }
        $model = new Contact;
        $page->content = str_replace('{{contact}}', $this->renderPartial('contact', ['model' => $model]), $page->content);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('page', ['model' => $page]);
    }
    
    /**
     * Displays reviews page
     *
     * @return string
     */
    public function actionReviews()
    {
        if (!$page = Pages::findOne(['slug' => 'reviews'])) {
            throw new NotFoundHttpException(Yii::t('main', 'Page not found.'));
        }
        $model = new Reviews;
        $request = Yii::$app->request;
        
        $page->content = str_replace('{{reviews_data}}', $this->renderPartial('reviews_data', [
            'dataProvider' => new ActiveDataProvider([
                'query' => Reviews::find()->where(['is_active' => 1])->orWhere(['ip' => Yii::$app->request->userIP])
            ])
        ]), $page->content);
        $page->content = str_replace('{{reviews_form}}', $this->renderPartial('reviews_form', ['model' => $model]), $page->content);
            
        if ($request->isAjax && $model->load($request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($model);
        }
        if ($model->load($request->post()) && $model->save()) {
            return $this->refresh();
        }
        return $this->render('page', ['model' => $page]);
    }
}