<?php

namespace app\modules\admin\controllers;

/**
 * ContactController implements the CRUD actions for Contact model.
 */
class ContactController extends AdminController
{
    public $modelClass = 'app\models\Contact';
    public $searchModelClass = 'app\models\ContactSearch';
    
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