<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CounteragentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchApplied bool */

$this->title = 'Контрагенты | '.Yii::$app->name;
$this->params['breadcrumbs'][] = 'Контрагенты';

$this->params['content-block'] = 'Контрагенты <a href="#manual" class="m-r-sm" data-toggle="collapse" aria-controls="manual" aria-expanded="false" title="Развернуть подсказку (показать инструкцию)"><i class="fa fa-question-circle"></i></a>';
$this->params['content-additional'] = 'В справочнике содержатся заказчики.';
?>
<div class="counteragents-list">
    <div id="manual" class="collapse text-justify">
        <div class="hpanel hyellow">
            <div class="panel-heading hbuilt">Инструкция</div>
            <div class="panel-body">
                <p>Список может содержать заказчиков и посредников.</p>
            </div>
        </div>
    </div>

    <p>
        <?= Html::a('<i class="fa fa-plus-circle"></i> Создать', ['create'], ['class' => 'btn btn-success']) ?>

        <?= Html::a('<i class="fa fa-search"></i> Отбор', ['#frm-search'], ['class' => 'btn btn-'.($searchApplied ? 'info' : 'default'), 'data-toggle' => 'collapse', 'aria-expanded' => 'false', 'aria-controls' => 'frm-search']) ?>

    </p>

    <?= $this->render('_search', ['model' => $searchModel, 'searchApplied' => $searchApplied,]); ?>

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
                'attribute' => 'type.name',
                'label' => 'Тип',
                'options' => ['width' => '80'],
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var \backend\models\Counteragents $model */
                    /** @var \yii\grid\DataColumn $column */
                    return Html::a($model->name, ['/counteragents/update', 'id' => $model->id]);
                },
            ],
            'phones',
            'comment:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
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