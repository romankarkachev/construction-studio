<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\Regions */

$this->title = $model->name.' '.HtmlPurifier::process('&mdash;').' Регионы | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Регионы', 'url' => ['/regions']];
if ($model->parent_id != null) $this->params['breadcrumbs'][] = ['label' => $model->parent->name, 'url' => ['/regions/'.$model->parent->id]];
$this->params['breadcrumbs'][] = $model->name;

$this->params['content-block'] = $model->name;
$this->params['content-additional'] = 'Редактирование существующего региона (населенного пункта)';
?>
<div class="regions-update">
    <?= $this->render('_form', ['model' => $model, 'parent_model' => $parent_model,]) ?>

</div>