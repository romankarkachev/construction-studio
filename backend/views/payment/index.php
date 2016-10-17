<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Оплата от контрагентов | '.Yii::$app->name;
$this->params['breadcrumbs'][] = 'Оплата';

$this->params['content-block'] = 'Оплата от контрагентов';
$this->params['content-additional'] = 'Содержатся документы по оплате работы контрагентами.';
?>
<div class="payment-list">
    <p>
        <?= Html::a('<i class="fa fa-plus-circle"></i> Создать', ['create'], ['class' => 'btn btn-success']) ?>

    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'hpanel'],
        'layout' => '<div class="panel-body">
            <div class="table-responsive">{items}</div>
        </div>
        <div class="panel-footer">{summary}<div class="pull-right">{pager}</div></div>',
        'tableOptions' => [
            'class' => 'table table-striped table-hover'
        ],
        'columns' => [
            [
                'attribute' => 'created_at',
                'format' =>  ['date', 'dd.MM.Y HH:mm'],
                'options' => ['width' => '150'],
                'headerOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle;'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pmName',
                'options' => ['width' => '80'],
                'headerOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle;'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'amount',
                'format' => ['decimal'],
                'options' => ['width' => '80'],
                'headerOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle;'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'currencyName',
                'options' => ['width' => '80'],
                'headerOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle;'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'counteragentName',
                'headerOptions' => ['style' => 'vertical-align: middle;'],
            ],
            [
                'attribute' => 'comment',
                'format' => 'ntext',
                'headerOptions' => ['style' => 'vertical-align: middle;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'headerOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle;'],
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fa fa-pencil"></i>', $url, ['title' => Yii::t('yii', 'Редактировать'), 'class' => 'btn btn-xs btn-default']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fa fa-trash-o"></i>', $url, ['title' => Yii::t('yii', 'Удалить'), 'class' => 'btn btn-xs btn-danger', 'aria-label' => Yii::t('yii', 'Delete'), 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'data-pjax' => '0',]);
                    }
                ],
                'options' => ['width' => '80'],
            ],
        ],
    ]); ?>
</div>