<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$app = Yii::$app;
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . $app->name . '</span>', $app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <?php $name = $app->user->name ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs"><?=$name?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <p>
                                <small><?= Yii::t('app', 'Registered') ?>: <b><?=$app->formatter->asDate($app->user->identity->created_at, 'long')?></b></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(Yii::t('app', 'Profile'), ['/admin/user/update', 'id' => $app->user->id], ['class' => 'btn btn-default btn-flat']) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(Yii::t('app', 'Logout'), ['/admin/admin/logout'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']) ?>
                            </div>
                        </li>
                    </ul>
                </li>
                
                <li><?= Html::a('<i class="fa fa-home"></i>', '/', ['title' => 'Сайт', 'class' => 'label dropdown-toggle', 'style' => 'font-size:120%', 'target' => '_blank']) ?></li>
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>