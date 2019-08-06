<?php

namespace app\modules\admin\controllers;

/**
 * PagesController implements the CRUD actions for Pages model.
 */
class PagesController extends AdminController
{
    public $modelClass = 'app\models\Pages';
    public $searchModelClass = 'app\models\PagesSearch';
}