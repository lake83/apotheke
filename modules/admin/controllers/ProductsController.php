<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\models\ProductsPrice;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends AdminController
{
    public $modelClass = 'app\models\Products';
    public $searchModelClass = 'app\models\ProductsSearch';
    
    public function actions()
    {
        $actions = parent::actions();
        
        $actions['prices'] = [
            'class' => 'app\modules\admin\controllers\actions\UpdateMultiple',
            'model' => $this->modelClass,
            'models' => 'app\models\ProductsPrice',
            'owner' => 'product_id',
            'view' => 'prices'
        ];
        return $actions;
    }
}