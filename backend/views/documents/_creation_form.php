<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Documents */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="documents-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'facilityName')->staticControl() ?>

        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'ca_id')->widget(Select2::className(), [
                'initValueText' => $model->customer->name,
                'theme' => Select2::THEME_BOOTSTRAP,
                'language' => 'ru',
                'options' => ['placeholder' => 'Введите наименование'],
                'pluginOptions' => [
                    'minimumInputLength' => 1,
                    'language' => 'ru',
                    'ajax' => [
                        'url' => Url::to(['counteragents/list-nf']),
                        'delay' => 250,
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(result) { return result.text; }'),
                    'templateSelection' => new JsExpression('function (result) { return result.text; }'),
                ],
            ]); ?>

        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'total_amount', ['template'=>'{label}<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-rub"></span></span></div>{error}'])->textInput(['placeholder' => 'Введите число']) ?>

        </div>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    </div>
    <div class="form-group">
        <?php if ($model->isNewRecord): ?>
            <?= Html::submitButton('<i class="fa fa-plus-circle" aria-hidden="true"></i> Создать', ['class' => 'btn btn-success btn-lg']) ?>

        <?php else: ?>
            <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить', ['class' => 'btn btn-primary btn-lg']) ?>

        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>