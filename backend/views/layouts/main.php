<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

backend\assets\AppAsset::register($this);

rmrevin\yii\fontawesome\AssetBundle::register($this);

romankarkachev\web\HomerAsset::register($this);

// представление имени пользователя: если указано имя, то оно (profile->name), а если нет - то имя пользователя (username)
$username = Yii::$app->user->identity->profile->name != '' ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->username;

// аватар
$profile_images = Yii::getAlias('@uploads-profiles').'/';
$avatar = $profile_images.'no-avatar.png';
if (Yii::$app->user->identity->profile->avatar_tfn != null && Yii::$app->user->identity->profile->avatar_tfn != '')
    if (file_exists(Yii::$app->user->identity->profile->avatar_tffp))
        $avatar = $profile_images.Yii::$app->user->identity->profile->avatar_tfn;
unset($profile_images);

// сайдбар (меню слева)
if (Yii::$app->user->can('root'))
    $items = [
        ['label' => 'Контрагенты', 'class' => 'text-primary', 'icon' => 'fa fa-male pull-right', 'url' => ['/counteragents']],
        ['label' => 'Объекты</', 'class' => 'text-info', 'icon' => 'fa fa-building-o pull-right', 'url' => ['/facilities']],
        ['label' => 'Оплата', 'class' => 'text-success', 'icon' => 'fa fa-money pull-right', 'url' => ['/payment']],
        [
            'label' => 'Справочники',
            'url' => '#',
            'items' => [
                ['label' => 'Номенклатура', 'url' => ['/ps']],
                ['label' => 'Единицы измерения', 'url' => ['/units']],
                ['label' => 'Валюта', 'url' => ['/currencies']],
                ['label' => 'Регионы', 'url' => ['/regions']],
                ['label' => 'Типы номенклатуры', 'url' => ['/ps-types']],
                ['label' => 'Типы контрагентов', 'url' => ['/ca-types']],
                ['label' => 'Способы оплаты', 'url' => ['/payment-methods']],
                ['label' => 'Статусы объектов', 'url' => ['/facilities-states']],
            ],
        ],
        ['label' => 'Пользователи', 'icon' => 'fa fa-users pull-right', 'url' => ['/users']],
    ];
else
    $items = [
        ['label' => 'Контрагенты', 'class' => 'text-primary', 'icon' => 'fa fa-male pull-right', 'url' => ['/counteragents']],
        ['label' => 'Объекты</', 'class' => 'text-info', 'icon' => 'fa fa-building-o pull-right', 'url' => ['/facilities']],
        ['label' => 'Оплата', 'class' => 'text-success', 'icon' => 'fa fa-money pull-right', 'url' => ['/payment']],
        [
            'label' => 'Справочники',
            'url' => '#',
            'items' => [
                ['label' => 'Номенклатура', 'url' => ['/ps']],
                ['label' => 'Единицы измерения', 'url' => ['/units']],
                ['label' => 'Регионы', 'url' => ['/regions']],
            ],
        ],
    ];
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
<body class="<?= Yii::$app->user->isGuest ? 'blank' : 'fixed-navbar' ?>">
<?php $this->beginBody() ?>
<?php if (Yii::$app->user->isGuest): ?>
    <div class="login-container">
        <?php if (!empty($this->params['content-block'])): ?>
            <h3 class="sub-header"><?= $this->params['content-block'] ?></h3>
        <?php endif; ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div> <!-- /container -->
<?php else: ?>
    <div class="boxed-wrapper">
        <!-- Заголовок -->
        <div id="header">
            <div id="logo" class="light-version"><i class="pe pe-7s-paint text-primary"></i> <span class="text-uppercase"><?= Yii::$app->name ?></span></div>
            <nav role="navigation">
                <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
                <div class="small-logo">
                    <span class="text-primary text-uppercase"><i class="pe pe-7s-paint text-primary"></i> <?= Yii::$app->name ?></span>
                </div>
                <div class="mobile-menu">
                    <button type="button" class="navbar-toggle mobile-menu-toggle" data-toggle="collapse" data-target="#mobile-collapse">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                    <div class="collapse mobile-navbar" id="mobile-collapse">
                        <ul class="nav navbar-nav">
                            <li><?= Html::a('Контрагенты', ['/counteragents']) ?></li>
                            <li><?= Html::a('Объекты', ['/facilities']) ?></li>
                            <li><?= Html::a('Выход ('.$username.')', ['/logout'], ['data-method' => 'post']) ?>
                        </ul>
                    </div>
                </div>
                <div class="navbar-right">
                    <ul class="nav navbar-nav no-borders">
                        <li class="dropdown">
                            <?= Html::a('<i class="pe-7s-upload pe-rotate-90"></i>', ['/logout'], ['data-method' => 'post', 'title' => 'Выход ('.$username.')']) ?>

                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Навигация -->
        <aside id="menu">
            <div id="navigation">
                <div class="profile-picture">
                    <?= Html::a('<img src="'.$avatar.'" class="img-circle m-b" alt="logo">', ['/profile']) ?>

                    <div class="stats-label text-color">
                        <span class="font-extra-bold font-uppercase"><?= $username ?></span>
                    </div>
                </div>
                <?= romankarkachev\widgets\Menu::widget(
                    [
                        'options' => ['id' => 'side-menu', 'class' => 'nav'],
                        'encodeLabels' => false,
                        'items' => $items,
                    ]
                ) ?>

            </div>
        </aside>

        <!-- Main Wrapper -->
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

            <!-- Контент -->
            <div class="content animate-panel">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>

            <!-- Подвал -->
            <footer class="footer">
                <span class="pull-right">&copy; Роман Каркачев <?= date('Y') ?></span>
                <span class="text-uppercase"><?= Yii::$app->name ?></span> <em>| учетная система</em>
            </footer>
        </div>
    </div>
<?php endif ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
