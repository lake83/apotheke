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
                <?= Html::a('<img style="position: absolute;" src="/images/flag.png" />', ['site/index']) ?>
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
        ['label' => Yii::t('main', 'Main'), 'url' => ['site/index']],
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
        <?php $this->beginBlock('mobile-products') ?>
        
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#product-menu-collapse" onclick="js:$('.navbar-collapse').collapse('hide');">
                <span class="sr-only">Toggle navigation</span><?= Yii::t('main', 'Product') ?>
            </button>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#service-collapse" onclick="js:$('.navbar-collapse').collapse('hide');">
                <span class="sr-only">Toggle navigation</span><?= Yii::t('main', 'Customer service') ?>
            </button>
            <div id="service-collapse" class="navbar-collapse collapse" aria-expanded="false">
                
                <?php $this->beginBlock('working', true) ?>
                
                <p><?= Yii::t('main', 'Working hours') ?>:</p>    
                <span><?= $params['working_hours'] ?></span>
                <hr />
                <p><?= Yii::t('main', 'Telephone Germany') ?>:</p>    
                <span><?= $params['working_phone'] ?></span>
                
                <?php $this->endBlock() ?>
                
            </div>
        
        <?php $this->endBlock();
        
        NavBar::begin([
            'id' => 'product-menu',
            'headerContent' => $this->blocks['mobile-products'],
            'options' => [
                'class' => 'navbar-inverse'
            ]
        ]);
        echo Nav::widget(['items' => $menu_items]);
        
        NavBar::end(); ?>
    </div>        

    <div class="container no-padding">
        <div class="col-md-2-5 hidden-xs">
            <div class="products-header"><?= Yii::t('main', 'Product') ?></div>
            <?= Nav::widget(['id' => 'left-column', 'items' => $menu_items]) ?>
            
            <div class="products-header"><?= Yii::t('main', 'Payment methods') ?></div>
            <div class="aside-column">
                <img src="/images/nachnahme-1.png" />
                <img src="/images/icon-vorkasse-blau.png" />
            </div>
            
            <div class="banner">
                <div class="column-banner" style="background-image: url('/images/banner1.png');"></div>
                <?= Yii::t('main', 'Cheaper, faster & easier with us') ?>
            </div>   
        </div>
        
        <div class="col-md-7 col-sm-7">
            <?= $content ?>
        </div>
        
        <div class="col-md-2-5 hidden-xs">
            <div class="products-header"><?= Yii::t('main', 'Customer service') ?></div>
            <div class="aside-column aside-right">    
                <?= $this->blocks['working'] ?>
            </div>
            
            <div class="banner">
               <div class="column-banner" style="background-image: url('/images/3-Dapoxetine-160mg-600x420.png');"></div>
               <?= Yii::t('main', 'Dapoxetine: up to 50% longer fun') ?>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="footer-content row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <p><?= Yii::t('main', 'Navigation') ?></p>
                <?= Nav::widget(['id' => 'footer-menu', 'items' => $items]) ?>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <p><?= Yii::t('main', 'Payment methods') ?></p>
                <img src="/images/nachnahme-1.png" />
                <img src="/images/icon-vorkasse-blau.png" />
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <p><?= Yii::t('main', 'Delivery') ?></p>
                <img src="/images/ftr-ups.png" />
                <img src="/images/ftr-ems.png" />
                <img src="/images/ftr-d.png" />
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <p><?= Yii::t('main', 'Contact details') ?></p>
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
                Coryright &copy; <?= date('Y') ?> Private Apotheke Deutschland 
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>