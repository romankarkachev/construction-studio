<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\FacilitiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchApplied bool */

$this->title = 'Объекты | '.Yii::$app->name;
$this->params['breadcrumbs'][] = 'Объекты';

$this->params['content-block'] = 'Объекты';
$this->params['content-additional'] = 'В справочнике содержатся обслуживаемые объекты.';
?>
<div class="facilities-list">
    <div id="manual" class="collapse text-justify">
        <div class="hpanel hyellow">
            <div class="panel-heading hbuilt">Инструкция</div>
            <div class="panel-body">
                <p>Список содержит объекты, которые Вы обслуживаете.</p>
            </div>
        </div>
    </div>

    <p>
        <?= Html::a('<i class="fa fa-plus-circle"></i> Создать', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-search"></i> Отбор', ['#frm-search'], ['class' => 'btn btn-'.($searchApplied ? 'info' : 'default'), 'data-toggle' => 'collapse', 'aria-expanded' => 'false', 'aria-controls' => 'frm-search']) ?>
    </p>

    <?= $this->render('_search', ['model' => $searchModel, 'searchApplied' => $searchApplied,]); ?>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '<div class="row">{items}</div>{pager}',
            // можно всегда выводить пагинатор
            // 'pager' => ['hideOnSinglePage' => false],
            'itemView' => function ($model, $key, $index, $widget) {
                return $this->render('_item', ['model' => $model, 'index' => $index]);
            },
        ]); ?>

</div>
