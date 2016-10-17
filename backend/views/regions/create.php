<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Regions */
/* @var $parent_model \backend\models\Regions */

$this->title = 'Новый регион (населенный пункт) | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Регионы', 'url' => ['/regions']];
$this->params['breadcrumbs'][] = 'Новый *';

$this->params['content-block'] = 'Новый регион (населенный пункт)';
$this->params['content-additional'] = 'Создание региона (населенного пункта)';
?>
<div class="regions-create">
    <?= $this->render('_form', ['model' => $model, 'parent_model' => $parent_model,]) ?>

</div>