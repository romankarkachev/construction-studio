<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use backend\models\FacilitiesStates;

/* @var $this yii\web\View */
/* @var $model backend\models\Facilities */
?>

<div class="col-md-6">
    <div class="hpanel<?= FacilitiesStates::getStatesColors($model->state->id)['panels'] ?>">
        <div class="panel-body">
            <span class="label label-<?= FacilitiesStates::getStatesColors($model->state->id)['labels'] ?> pull-right"><?= $model->state->name ?></span>
            <div class="row">
                <div class="col-sm-8">
                    <h4 style="margin-top: 0px;"<?= FacilitiesStates::getStatesColors($model->state->id)['headers'] ?>><?= Html::a($model->name, ['update', 'id' => $model->id]) ?></h4>
                    <p>
                        <small><?= ($model->region_id != null ? ($model->region->parent_id != null ? $model->region->parent->name.' '.$model->region->name : $model->region->name).($model->address != null && $model->address != '' ? ', '.$model->address : '') : '')   ?></small>
                    </p>
                    <p>
                        Заказчик: <?= Html::a($model->customer->name_short, ['/counteragents/update', 'id' => $model->customer_id], ['target' => '_blank', 'title' => 'Открыть в новом окне']).($model->customer->phones != null && $model->customer->phones != '' ? ' ('.$model->customer->phones.')' : '') ?>
                    </p>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="project-label"><?= $model->attributeLabels()['documentsCount'] ?></div>
                            <small><?= $model->documentsCount ?></small>
                        </div>
                        <div class="col-sm-3">
                            <div class="project-label"><?= $model->attributeLabels()['filesCount'] ?></div>
                            <small><?= $model->filesCount ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 project-info">
                    <div class="project-action m-t-md">
                        <div class="btn-group">
                            <?= Html::a('<i class="fa fa-plus-circle" aria-hidden="true"></i>', ['/documents/create', 'f_id' => $model->id], ['class' => 'btn btn-xs btn-success', 'title' => 'Создать документ к этому объекту']) ?>

                            <?= Html::a('<i class="fa fa-pencil" aria-hidden="true"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-xs btn-default', 'title' => 'Редактировать объект']) ?>

                            <?= Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i> Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger btn-xs',
                                'title' => 'Удалить объект',
                                'data' => [
                                    'confirm' => 'Вместе с объектом удаляюся все прикрепленные данные (файлы к объекту, документам, строкам табличных частей, сами документы). Удалить объект?',
                                    'method' => 'post',
                                ],
                            ]) ?>

                        </div>
                    </div>
                    <div class="project-value">
                        <h3 class="text-success">
                            <?= Yii::$app->formatter->asCurrency($model->documentsAmount) ?>

                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            Обновлен <?= Yii::$app->formatter->asDate($model->updated_at, 'php:d.m.Y в H:i') ?>
        </div>
    </div>
</div>

<?php if ($index % 2 == 1): ?>
</div><!-- баклажан -->
<div class="row">
<?php endif; ?>
