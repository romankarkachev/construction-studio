<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Facilities */

$this->title = 'Новый объект | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Объекты', 'url' => ['/facilities']];
$this->params['breadcrumbs'][] = 'Новый *';

$this->params['content-block'] = 'Новый объект';
$this->params['content-additional'] = 'Создание нового объекта';
?>
<div class="facilities-create">
    <?= $this->render('_creation_form', ['model' => $model]) ?>

</div>