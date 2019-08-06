<?php

namespace app\modules\admin\controllers;

/**
 * TrafficController implements the CRUD actions for Traffic model.
 */
class TrafficController extends AdminController
{
    public $modelClass = 'app\models\Traffic';
    public $searchModelClass = 'app\models\TrafficSearch';
}