<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\PaymentMethods */

$this->title = $model->name.' '.HtmlPurifier::process('&mdash;').' Способы оплаты | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Способы оплаты', 'url' => ['/payment-methods']];
$this->params['breadcrumbs'][] = $model->name;

$this->params['content-block'] = $model->name;
$this->params['content-additional'] = 'Редактирование существующего типа контрагентов';
?>
<div class="payment-methods-update">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>