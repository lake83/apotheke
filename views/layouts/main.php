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
$params = Yii::$app->params;
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
    <div class="header hidden-xs">
        <div class="container">
            <div class="logo">
                <?= Html::a(Yii::t('main', 'Private pharmacy Germany'), ['site/index'], ['class' => 'logo-title']) ?>
                <p><?= Yii::t('main', 'Security Discretion Trust Quality') ?></p>
                <?php Pjax::begin(['id' => 'menu-cart']) ?>            
                <?= Html::a('<b class="hidden-xs hidden-sm">' . Yii::t('main', 'Shopping cart') . ':</b> <strong>' . Yii::$app->formatter->asCurrency($session['cart']['sum'] ?:0) . '</strong> <span>' . ($session['cart']['quantity'] ?:'0') . '</span>
                <em><img src="/images/shopping-cart.png" width="30" height="28"/></em>', ['shop/cart'], ['class' => 'cart', 'data-pjax' => 0]) ?>
                <?php Pjax::end() ?>
            </div>
        </div>
    </div>

    <?php
    $this->beginBlock('mobile-cart');
        Pjax::begin(['id' => 'mobile-menu-cart']);           
            echo Html::a('<span>' . ($session['cart']['quantity'] ?:'0') . '</span><strong>' . Yii::$app->formatter->asCurrency($session['cart']['sum'] ?:0) . '</strong>
                <em><img src="/images/shopping-cart2.png" width="36" height="32"/></em>', ['shop/cart'], ['class' => 'mobile-cart', 'data-pjax' => 0]);
        Pjax::end();
    $this->endBlock();
    
    NavBar::begin([
        'id' => 'main-menu',
        'headerContent' => '<div class="menu-mobile visible-xs">' . Yii::t('main', 'Menu') . $this->blocks['mobile-cart'] . '</div>',
        'options' => [
            'class' => 'navbar-inverse'
        ]
    ]);
    $items = [
        ['label' => Yii::t('main', 'About us'), 'url' => ['site/page', 'slug' => 'about'], 'active' => $slug == 'about'],
        ['label' => Yii::t('main', 'Answers & questions'), 'url' => ['site/page', 'slug' => 'faq'], 'active' => $slug == 'faq'],
        ['label' => Yii::t('main', 'Contact'), 'url' => ['site/contact']],
        ['label' => Yii::t('main', 'Reviews'), 'url' => ['site/reviews']],
        ['label' => Yii::t('main', 'Delivery & payment'), 'url' => ['site/page', 'slug' => 'delivery-payment'], 'active' => $slug == 'delivery-payment']
    ];
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $items
    ]);
    NavBar::end();
    ?>
    
    <div class="header-mobile visible-xs">
        <div>
            <?= Html::a(Yii::t('main', 'Private pharmacy Germany'), ['site/index'], ['class' => 'logo-title']) ?>
            <p><?= Yii::t('main', 'Security Discretion Trust Quality') ?></p>
        </div>
        <div class="logo-flag"></div>
    </div>        

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
        <div class="footer-content row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <p><?= Yii::t('main', 'NAVIGATION') ?></p>
                <?= Nav::widget(['id' => 'footer-menu', 'items' => $items]) ?>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <p><?= Yii::t('main', 'PAYMENT METHODS') ?></p>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <p><?= Yii::t('main', 'DELIVERY') ?></p>
                <img src="/images/ftr-ups.png" />
                <img src="/images/ftr-ems.png" />
                <img src="/images/ftr-d.png" />
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <p><?= Yii::t('main', 'CONTACT DETAILS') ?></p>
                <div class="col-md-12 col-xs-6 no-padding">
                    <?= Yii::t('main', 'Working hours') ?>:
                    <span><?= $params['working_hours'] ?></span>
                </div>
                <div class="col-md-12 col-xs-6 no-padding">
                    <?= Yii::t('main', 'Telephone Germany') ?>:
                    <span><?= $params['working_phone'] ?></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <?= Yii::t('main', 'Copyright') ?> &copy; <?= date('Y') ?>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>