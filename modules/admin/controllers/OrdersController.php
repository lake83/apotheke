<?php

namespace app\modules\admin\controllers;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends AdminController
{
    public $modelClass = 'app\models\Orders';
    public $searchModelClass = 'app\models\OrdersSearch';
}