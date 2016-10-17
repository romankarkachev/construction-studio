<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\FacilitiesStates */

$this->title = $model->name.' '.HtmlPurifier::process('&mdash;').' Статусы объектов | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Статусы объектов', 'url' => ['/facilities-states']];
$this->params['breadcrumbs'][] = $model->name;

$this->params['content-block'] = $model->name;
$this->params['content-additional'] = 'Редактирование существующего статуса объектов';
?>
<div class="facilities-states-update">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>