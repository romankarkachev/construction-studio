<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\web\View;
use yii\widgets\Pjax;
use dektrium\user\models\UserSearch;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var UserSearch $searchModel
 */

$this->title = Yii::t('user', 'Manage users').' | '.Yii::$app->name;
$this->params['breadcrumbs'][] = $this->title;

$this->params['content-block'] = 'Пользователи';
$this->params['content-additional'] = 'Создание, удаление пользователей, а также изменение информации о них, установка паролей, блокировка.';
?>

<?= $this->render('/_alert', [
    'module' => Yii::$app->getModule('user'),
]) ?>

<p>
    <?= Html::a('<i class="fa fa-plus-circle"></i> Создать', ['/users/create'], ['class' => 'btn btn-success']) ?>
</p>
<?php Pjax::begin() ?>

<?= GridView::widget([
    'dataProvider' 	=> $dataProvider,
    'options' => ['class' => 'hpanel'],
    'layout' => '<div class="panel-body">
            <div class="table-responsive">{items}</div>
        </div>
        <div class="panel-footer">{summary}<div class="pull-right">{pager}</div></div>',
    'tableOptions' => [
        'class' => 'table table-striped table-hover'
    ],
    'columns' => [
        'username',
        'email:email',
        [
            'attribute' => 'registration_ip',
            'value' => function ($model) {
                return $model->registration_ip == null
                    ? '<span class="not-set">' . Yii::t('user', '(not set)') . '</span>'
                    : $model->registration_ip;
            },
            'format' => 'html',
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                if (extension_loaded('intl')) {
                    return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
                } else {
                    return date('Y-m-d G:i:s', $model->created_at);
                }
            },
            'filter' => DatePicker::widget([
                'model'      => $searchModel,
                'attribute'  => 'created_at',
                'dateFormat' => 'php:Y-m-d',
                'options' => [
                    'class' => 'form-control',
                ],
            ]),
        ],
        [
            'header' => Yii::t('user', 'Confirmation'),
            'value' => function ($model) {
                if ($model->isConfirmed) {
                    return '<div class="text-center"><span class="text-success">' . Yii::t('user', 'Confirmed') . '</span></div>';
                } else {
                    return Html::a(Yii::t('user', 'Confirm'), ['confirm', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                    ]);
                }
            },
            'format' => 'raw',
            'visible' => Yii::$app->getModule('user')->enableConfirmation,
        ],
        [
            'header' => Yii::t('user', 'Block status'),
            'value' => function ($model) {
                if ($model->isBlocked) {
                    return Html::a(Yii::t('user', 'Unblock'), ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-success btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                    ]);
                } else {
                    return Html::a(Yii::t('user', 'Block'), ['block', 'id' => $model->id], [
                        'class' => 'btn btn-xs btn-danger btn-block',
                        'data-method' => 'post',
                        'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                    ]);
                }
            },
            'format' => 'raw',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Действия',
            'template'=>'{update} {delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    /* @var $model \dektrium\user\models\User */

                    return Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', ['/users/update', 'id' => $model->id], ['class' => 'btn btn-info btn-xs', 'title' => 'Редактировать', 'data-pjax' => '0']);
                },
                'delete' => function ($url, $model) {
                    /* @var $model \dektrium\user\models\User */
                    
                    return Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i>', ['/users/delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger btn-xs',
                        'title' => 'Удалить элемент',
                        'data-confirm' => 'Вы действительно хотите удалить этот элемент?',
                        'data-method' => 'post',
                    ]);
                }
            ],
            'options' => ['width' => 80, 'text-align' => 'center'],
        ],
    ],
]); ?>

<?php Pjax::end() ?>
