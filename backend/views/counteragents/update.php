<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\Counteragents */

$this->title = $model->name.' '.HtmlPurifier::process('&mdash;').' Контрагенты | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Контрагенты', 'url' => ['/counteragents']];
$this->params['breadcrumbs'][] = $model->name;

$this->params['content-block'] = $model->name;
$this->params['content-additional'] = 'Редактирование существующего контрагента';
?>
<div class="counteragents-update">
    <?= $this->render('_form', ['model' => $model,]) ?>

</div>