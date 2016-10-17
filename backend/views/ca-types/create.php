<?php

/* @var $this yii\web\View */
/* @var $model backend\models\CATypes */

$this->title = 'Новый тип контрагентов | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы контрагентов', 'url' => ['/ca-types']];
$this->params['breadcrumbs'][] = 'Новый *';

$this->params['content-block'] = 'Новый тип контрагентов';
$this->params['content-additional'] = 'Создание нового типа контрагентов';
?>
<div class="ca-types-create">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>