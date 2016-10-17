<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\PS */

$this->title = $model->name.' '.HtmlPurifier::process('&mdash;').' Номенклатура | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Номенклатура', 'url' => ['/ps']];
$this->params['breadcrumbs'][] = $model->name;

$this->params['content-block'] = $model->name;
$this->params['content-additional'] = 'Редактирование существующей номенклатурной позиции.';
?>
<div class="ps-update">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>