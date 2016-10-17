<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\Documents */
/* @var $dp_files \yii\data\ActiveDataProvider */
/* @var $dp_table_part \yii\data\ActiveDataProvider */
/* @var $summaries array */

$this->title = 'Документ '.$model->id.' '.HtmlPurifier::process('&mdash;').' Объект '.$model->facility->name.' | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Объекты', 'url' => ['/facilities']];
$this->params['breadcrumbs'][] = ['label' => $model->facility->name, 'url' => ['/facilities/update', 'id' => $model->facility_id]];
$this->params['breadcrumbs'][] = 'Документ '.$model->id;

$this->params['content-block'] = 'Документ '.$model->id;
$this->params['content-additional'] = 'Редактирование документа. Создан '.Yii::$app->formatter->asDate($model->created_at, 'php:d F Y в H:i');
?>
<div class="documents-update">
    <?= $this->render('_form', [
        'model' => $model,
        'dp_files' => $dp_files,
        'dp_table_part' => $dp_table_part,
        'summaries' => $summaries,
    ]) ?>

</div>