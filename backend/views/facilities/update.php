<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\Facilities */
/* @var $documents \yii\data\ActiveDataProvider */
/* @var $files \yii\data\ActiveDataProvider */

$this->title = $model->name.' '.HtmlPurifier::process('&mdash;').' Объекты | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Объекты', 'url' => ['/facilities']];
$this->params['breadcrumbs'][] = $model->name;

$this->params['content-block'] = $model->name;
$this->params['content-additional'] = 'Редактирование существующего объекта. Создан '.Yii::$app->formatter->asDate($model->created_at, 'php:d F Y в H:i');
?>
<div class="facilities-update">
    <?= $this->render('_form', [
        'model' => $model,
        'documents' => $documents,
        'files' => $files,
    ]) ?>

</div>