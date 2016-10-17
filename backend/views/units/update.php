<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\Units */

$this->title = $model->name.' '.HtmlPurifier::process('&mdash;').' Единицы измерения | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Единицы измерения', 'url' => ['/units']];
$this->params['breadcrumbs'][] = $model->name;

$this->params['content-block'] = $model->name;
$this->params['content-additional'] = 'Редактирование существующей единицы измерения';
?>
<div class="units-update">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>