<?php

namespace app\modules\admin\controllers;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends AdminController
{
    public $modelClass = 'app\models\Payment';
    public $searchModelClass = 'app\models\PaymentSearch';
}