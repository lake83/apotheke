<?php

namespace app\modules\admin\controllers;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends AdminController
{
    public $modelClass = 'app\models\Products';
    public $searchModelClass = 'app\models\ProductsSearch';
}