<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RegionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parent_model \backend\models\Regions */

$this->title = 'Регионы | '.Yii::$app->name;
if ($parent_model !== null) {
    $this->params['breadcrumbs'][] = ['label' => 'Регионы', 'url' => ['/regions']];
    $this->params['breadcrumbs'][] = $parent_model->name;

    $this->params['content-block'] = 'Населенные пункты '.$parent_model->name;

    $btn_create_url = ['create', 'parent_id' => $parent_model->id];
}
else {
    $this->params['breadcrumbs'][] = 'Регионы';

    $this->params['content-block'] = 'Регионы';

    $btn_create_url = ['create'];
}

$this->params['content-additional'] = 'В справочнике содержатся регионы и населенные пункты.';
?>
<div class="regions-list">
    <p>
        <?= Html::a('<i class="fa fa-plus-circle"></i> Создать', $btn_create_url, ['class' => 'btn btn-success']) ?>

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
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var \backend\models\Regions $model */
                    /** @var \yii\grid\DataColumn $column */
                    if ($model->parent_id == null)
                        return Html::a($model->name, ['/regions/'.$model->id]);
                    else
                        return $model->name;
                },
                'contentOptions' => ['style' => 'vertical-align: middle;'],
            ],
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