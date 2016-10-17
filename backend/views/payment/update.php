<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\Payment */

$this->title = 'Документ по оплате #'.$model->id.' '.HtmlPurifier::process('&mdash;').' Оплата | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Оплата', 'url' => ['/payment']];
$this->params['breadcrumbs'][] = 'Документ по оплате #'.$model->id;

$this->params['content-block'] = 'Документ по оплате #'.$model->id;
$this->params['content-additional'] = 'Редактирование документа по оплате от контрагента';
?>
<div class="payment-update">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>