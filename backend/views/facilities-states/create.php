<?php

/* @var $this yii\web\View */
/* @var $model backend\models\FacilitiesStates */

$this->title = 'Новый статус объектов | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Статусы объектов', 'url' => ['/facilities-states']];
$this->params['breadcrumbs'][] = 'Новый *';

$this->params['content-block'] = 'Новый статус объектов';
$this->params['content-additional'] = 'Создание нового статуса объектов';
?>
<div class="facilities-states-create">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>