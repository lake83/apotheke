<?php

namespace app\modules\admin\controllers;

use Yii;

/**
 * CouponController implements the CRUD actions for Coupon model.
 */
class CouponController extends AdminController
{
    public $modelClass = 'app\models\Coupon';
    public $searchModelClass = 'app\models\CouponSearch';
    
    public function actions()
    {
        $actions = parent::actions();
        
        unset($actions['create']);
        
        return $actions;
    }
    
    /**
     * Create coupons
     * 
     * @return string
     */
    public function actionCreate()
    {
        $model = new $this->modelClass;
        
        if ($model->load(Yii::$app->request->post())) {
            $saved = 0;
            
            foreach (explode("\n", $model->code) as $code) {
                $coupon = new $this->modelClass;
                $coupon->name = $model->name;
                $coupon->code = trim($code);
                $coupon->type = $model->type;
                $coupon->value = $model->value;
                $coupon->date_from = $model->date_from;
                $coupon->date_to = $model->date_to;
                $coupon->is_active = $model->is_active;
                
                if ($coupon->save()) {
                    $saved++;
                }
            }
            Yii::$app->session->setFlash('success', Yii::t('app', 'Record added: {saved}.', ['saved' => $saved]));
            return $this->redirect(['index']);
        }
        return $this->render('create', [ 'model' => $model]);
    }
}