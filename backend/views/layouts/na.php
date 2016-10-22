<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

backend\assets\AppAsset::register($this);

rmrevin\yii\fontawesome\AssetBundle::register($this);

romankarkachev\web\HomerAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/favicon.png']) ?>
    <?= $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/ico', 'href' => '/favicon.ico']) ?>
    <?php $this->head() ?>
</head>
<body class="blank hide-sidebar">
<?php $this->beginBody() ?>
    <div class="boxed-wrapper">
        <div id="header">
            <div id="logo" class="light-version"><i class="pe pe-7s-paint text-primary"></i> <span class="text-uppercase"><?= Yii::$app->name ?></span></div>
            <nav role="navigation">
                <div class="small-logo">
                    <span class="text-primary text-uppercase"><i class="pe pe-7s-paint text-primary"></i> <?= Yii::$app->name ?></span>
                </div>
            </nav>
        </div>

        <div id="wrapper">
            <div class="small-header transition animated fadeIn">
                <div class="hpanel">
                    <div class="panel-body">
                        <div id="hbreadcrumb" class="pull-right">
                            <?= Breadcrumbs::widget([
                                'tag' => 'ol',
                                'options' => ['class' => 'hbreadcrumb breadcrumb'],
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ]) ?>

                        </div>
    <?php if (!empty($this->params['content-block'])): ?>

                        <h2 class="font-light m-b-xs">
                            <?= $this->params['content-block'] ?>

                        </h2>
    <?php endif; ?>
    <?php if (!empty($this->params['content-additional'])): ?>

                        <small><?= $this->params['content-additional'] ?></small>
    <?php endif; ?>

                    </div>
                </div>
            </div>

            <div class="content animate-panel">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>

            <!-- Подвал
            <footer class="footer">
                <span class="pull-right">&copy; Роман Каркачев <?= date('Y') ?></span>
                <span class="text-uppercase"><?= Yii::$app->name ?></span> <em>| учетная система</em>
            </footer> -->
        </div>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
