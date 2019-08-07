<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use app\models\Pages;

AppAsset::register($this);

$slug = Yii::$app->request->get('slug');
$menu_items = [];
foreach (Pages::find()->select(['name', 'slug'])->where(['is_active' => 1, 'is_product' => 1])->orderBy('position ASC')->asArray()->all() as $link) {
    $menu_items[] = ['label' => $link['name'], 'url' => ['site/page', 'slug' => $link['slug']]];
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="header">
    
    </div>
    <?php
    NavBar::begin([
        'id' => 'main-menu',
        'options' => [
            'class' => 'navbar-inverse'
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Über uns', 'url' => ['site/page', 'slug' => 'about'], 'active' => $slug == 'about'],
            ['label' => 'Fragen&Antworten', 'url' => ['site/page', 'slug' => 'faq'], 'active' => $slug == 'faq'],
            ['label' => 'Kontakt', 'url' => ['site/contact']],
            ['label' => 'Kundenerfahrungen', 'url' => ['site/page', 'slug' => 'reviews'], 'active' => $slug == 'reviews'],
            ['label' => 'Lieferung&Bezahlung', 'url' => ['site/page', 'slug' => 'delivery-payment'], 'active' => $slug == 'delivery-payment']
        ]
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <div class="col-md-3">
            <?= Nav::widget(['items' => $menu_items]) ?>
        </div>
        <div class="col-md-9">
            <?= $content ?>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
