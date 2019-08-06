<?php

namespace app\modules\admin\controllers;

/**
 * DeliveryController implements the CRUD actions for Delivery model.
 */
class DeliveryController extends AdminController
{
    public $modelClass = 'app\models\Delivery';
    public $searchModelClass = 'app\models\DeliverySearch';
}