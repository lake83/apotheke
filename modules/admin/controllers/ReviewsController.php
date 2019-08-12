<?php

namespace app\modules\admin\controllers;

/**
 * ReviewsController implements the CRUD actions for Reviews model.
 */
class ReviewsController extends AdminController
{
    public $modelClass = 'app\models\Reviews';
    public $searchModelClass = 'app\models\ReviewsSearch';
    
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