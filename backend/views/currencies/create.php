<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Currencies */

$this->title = 'Новая валюта | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Валюта', 'url' => ['/currencies']];
$this->params['breadcrumbs'][] = 'Новая *';

$this->params['content-block'] = 'Новая валюта';
$this->params['content-additional'] = 'Создание новой валюты';
?>
<div class="currencies-create">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>