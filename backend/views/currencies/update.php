<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\Currencies */

$this->title = $model->name.' '.HtmlPurifier::process('&mdash;').' Валюты | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Валюты', 'url' => ['/currencies']];
$this->params['breadcrumbs'][] = $model->name;

$this->params['content-block'] = $model->name;
$this->params['content-additional'] = 'Редактирование существующей валюты';
?>
<div class="currencies-update">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>