<?php
app\assets\AdminAsset::register($this);

/* @var $this \yii\web\View */
/* @var $content string */

?>
<aside class="main-sidebar">
    <section class="sidebar">
<?= dmstr\widgets\Menu::widget([
    'options' => ['class' => 'sidebar-menu', 'data-widget' => 'tree'],
    'encodeLabels' => false,
    'items' => [
        ['label' => Yii::t('app', 'Users'), 'url' => ['user/index'], 'icon' => 'users'],
        ['label' => Yii::t('app', 'Orders'), 'url' => ['orders/index'], 'icon' => 'table'],
        ['label' => Yii::t('app', 'Products'), 'url' => ['products/index'], 'icon' => 'shopping-basket'],
        ['label' => Yii::t('app', 'Payment'), 'url' => ['payment/index'], 'icon' => 'tag'],
        ['label' => Yii::t('app', 'Delivery'), 'url' => ['delivery/index'], 'icon' => 'car'],
        ['label' => Yii::t('app', 'Traffic'), 'url' => ['traffic/index'], 'icon' => 'arrows'],
        ['label' => Yii::t('app', 'Content'), 'url' => ['pages/index'], 'icon' => 'book'],
        ['label' => Yii::t('app', 'Coupon'), 'url' => ['coupon/index'], 'icon' => 'file'],
        ['label' => Yii::t('app', 'Contact'), 'url' => ['contact/index'], 'icon' => 'envelope'],
        ['label' => Yii::t('app', 'Reviews'), 'url' => ['reviews/index'], 'icon' => 'comment']
    ]
]);	
?>
    </section>
</aside>