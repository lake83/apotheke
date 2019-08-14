<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use app\models\Pages;
use yii\widgets\Pjax;

AppAsset::register($this);

$slug = Yii::$app->request->get('slug');
$menu_items = [];
foreach (Pages::find()->select(['name', 'slug'])->where(['is_active' => 1, 'is_product' => 1])->orderBy('position ASC')->asArray()->all() as $link) {
    $menu_items[] = ['label' => $link['name'], 'url' => ['site/page', 'slug' => $link['slug']]];
}
$session = Yii::$app->session;
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
        <div class="container">
            <div class="logo">
            <?= Html::a(Yii::t('main', 'Private pharmacy Germany'), ['site/index'], ['class' => 'logo']) ?>
            <?php Pjax::begin(['id' => 'menu-cart']) ?>            
            <?= Html::a('<b class="hidden-xs hidden-sm">' . Yii::t('main', 'Shopping cart') . ':</b> € <strong>' . ($session['cart']['sum'] ?:'0.00') . '</strong> <span>' . ($session['cart']['quantity'] ?:'0') . '</span>', ['shop/cart'], ['class' => 'cart', 'data-pjax' => 0]) ?>
            <?php Pjax::end() ?>
            </div>
        </div>
    </div>
    <?php
    NavBar::begin([
        'id' => 'main-menu',
        'options' => [
            'class' => 'navbar-inverse'
        ]
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => Yii::t('main', 'About us'), 'url' => ['site/page', 'slug' => 'about'], 'active' => $slug == 'about'],
            ['label' => Yii::t('main', 'Answers & questions'), 'url' => ['site/page', 'slug' => 'faq'], 'active' => $slug == 'faq'],
            ['label' => Yii::t('main', 'Contact'), 'url' => ['site/contact']],
            ['label' => Yii::t('main', 'Reviews'), 'url' => ['site/page', 'slug' => 'reviews'], 'active' => $slug == 'reviews'],
            ['label' => Yii::t('main', 'Delivery & payment'), 'url' => ['site/page', 'slug' => 'delivery-payment'], 'active' => $slug == 'delivery-payment']
        ]
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <div class="col-md-3">
            <div class="products-header">Potenzmittel</div>
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
