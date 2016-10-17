<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PSTypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы номенклатуры | '.Yii::$app->name;
$this->params['breadcrumbs'][] = 'Типы номенклатуры';

$this->params['content-block'] = 'Типы номенклатуры';
$this->params['content-additional'] = 'В справочнике содержатся типы номенклатуры, например: услуга, материал.';
?>
<div class="ps-types-list">
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
            'name',
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