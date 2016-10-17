<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\CATypes */

$this->title = $model->name.' '.HtmlPurifier::process('&mdash;').' Типы контрагентов | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы контрагентов', 'url' => ['/ca-types']];
$this->params['breadcrumbs'][] = $model->name;

$this->params['content-block'] = $model->name;
$this->params['content-additional'] = 'Редактирование существующего типа контрагентов';
?>
<div class="ca-types-update">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>