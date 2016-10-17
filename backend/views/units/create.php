<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Units */

$this->title = 'Новая единица измерения | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Единицы измерения', 'url' => ['/units']];
$this->params['breadcrumbs'][] = 'Новая *';

$this->params['content-block'] = 'Новая единица измерения';
$this->params['content-additional'] = 'Создание новой единицы измерения';
?>
<div class="units-create">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>