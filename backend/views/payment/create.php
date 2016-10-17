<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Payment */

$this->title = 'Новый документ по оплате | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Оплата', 'url' => ['/payment']];
$this->params['breadcrumbs'][] = 'Новый *';

$this->params['content-block'] = 'Новый документ по оплате';
$this->params['content-additional'] = 'Создание нового документа по оплате от контрагента';
?>
<div class="payment-create">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>