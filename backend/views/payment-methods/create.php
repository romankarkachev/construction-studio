<?php

/* @var $this yii\web\View */
/* @var $model backend\models\PaymentMethods */

$this->title = 'Новый способ оплаты | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Способы оплаты', 'url' => ['/payment-methods']];
$this->params['breadcrumbs'][] = 'Новый *';

$this->params['content-block'] = 'Новый способ оплаты';
$this->params['content-additional'] = 'Создание нового способа оплаты';
?>
<div class="payment-methods-create">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>