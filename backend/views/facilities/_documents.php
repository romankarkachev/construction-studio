<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use common\components\TotalsColumn;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<div class="documents-list">
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}{pager}',
            'tableOptions' => ['class' => 'table table-striped table-hover'],
            'columns' => [
                [
                    'attribute' => 'created_at',
                    'format' =>  ['date', 'dd.MM.Y HH:mm'],
                    'options' => ['width' => '150'],
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                    'footer' => 'Итого:',
                    'footerOptions' => ['class' => 'text-right'],
                ],
                [
                    'class' => TotalsColumn::className(), // доработанная версия с выводом итогов в подвале таблицы
                    'attribute' => 'total_amount',
                    'format' => 'decimal',
                    'options' => ['width' => '120'],
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],
                [
                    'class' => TotalsColumn::className(), // доработанная версия с выводом итогов в подвале таблицы
                    'attribute' => 'tpAmount',
                    'format' => 'decimal',
                    'options' => ['width' => '100'],
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],
                [
                    'class' => TotalsColumn::className(), // доработанная версия с выводом итогов в подвале таблицы
                    'attribute' => 'rowsCount',
                    'options' => ['width' => '70'],
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],
                [
                    'class' => TotalsColumn::className(), // доработанная версия с выводом итогов в подвале таблицы
                    'attribute' => 'filesCount',
                    'options' => ['width' => '70'],
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center'],
                ],
                'comment',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'template' => '{update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<i class="fa fa-pencil"></i>', ['/documents/update', 'id' => $model->id], ['title' => Yii::t('yii', 'Редактировать'), 'class' => 'btn btn-xs btn-default']);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<i class="fa fa-trash-o"></i>', ['/documents/delete', 'id' => $model->id], ['title' => Yii::t('yii', 'Удалить'), 'class' => 'btn btn-xs btn-danger', 'aria-label' => Yii::t('yii', 'Delete'), 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'data-pjax' => '0',]);
                        }
                    ],
                    'options' => ['width' => '80'],
                ],
            ],
            'showFooter' => true,
            'footerRowOptions' => ['class' => 'text-center font-bold'],
        ]); ?>
    </div>
</div>