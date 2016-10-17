<?php

/* @var $this yii\web\View */
/* @var $model backend\models\PS */

$this->title = 'Новая номенклатура | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Номенклатура', 'url' => ['/ps']];
$this->params['breadcrumbs'][] = 'Новая *';

$this->params['content-block'] = 'Новая номенклатура';
$this->params['content-additional'] = 'Создание новой номенклатуры';
?>
<div class="ps-create">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>