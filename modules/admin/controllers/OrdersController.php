<?php

namespace app\modules\admin\controllers;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends AdminController
{
    public $modelClass = 'app\models\Orders';
    public $searchModelClass = 'app\models\OrdersSearch';
    
    public function actions()
    {
        $actions = parent::actions();
        
        $actions['view'] = [
            'class' => 'app\modules\admin\controllers\actions\View',
            'model' => $this->modelClass
        ];
        return $actions;
    }
}