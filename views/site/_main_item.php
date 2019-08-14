<?php

/* @var $this \yii\web\View */
/* @var $model \app\models\Products */

use yii\helpers\Url;
use app\components\SiteHelper;
?>

<div class="thumbnail col-md-4">
    <a href="<?= Url::to(['site/page', 'slug' => $model->slug]) ?>">
       <div class="prod-title"><?= $model->name ?></div>
       <div class="product_img" title="<?=$model->name?>" style="background: url('<?= SiteHelper::resized_image($model->image, 250, null) ?>') no-repeat;"></div>
    </a>
</div>