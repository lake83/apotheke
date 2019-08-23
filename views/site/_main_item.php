<?php

/* @var $this \yii\web\View */
/* @var $model \app\models\Products */

use yii\helpers\Url;
use app\components\SiteHelper;
?>

<div class="col-md-4 col-sm-6 col-xs-6 products-grid-item">
    <a class="product-item" href="<?= Url::to(['site/page', 'slug' => $model->slug]) ?>">
       <div class="buy-product"><?= $model->name ?></div>
       <div class="product_img" title="<?=$model->name?>" style="background-image: url('<?= SiteHelper::resized_image($model->image, 250, null) ?>');"></div>
    </a>
</div>