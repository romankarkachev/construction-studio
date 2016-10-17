<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Counteragents */

$this->title = 'Новый контрагент | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['/counteragents']];
$this->params['breadcrumbs'][] = 'Новый *';

$this->params['content-block'] = 'Новый контрагент';
$this->params['content-additional'] = 'Создание нового контрагента';
?>
<div class="counteragents-create">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>