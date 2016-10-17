<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use backend\models\CATypes;

/* @var $this yii\web\View */
/* @var $model backend\models\Counteragents */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="counteragents-form">
    <div class="hpanel">
        <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'name_short')->textInput(['maxlength' => true]) ?>

            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'name_full')->textInput(['maxlength' => true]) ?>

            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($model, 'ct_id')->widget(Select2::className(), [
                    'data' => CATypes::ArrayMap(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '- выберите -'],
                ]) ?>

            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'birthdate')->widget(DateControl::className(), [
                    'value' => $model->birthdate,
                    'type' => DateControl::FORMAT_DATE,
                    'displayFormat' => 'php:d.m.Y',
                    'saveFormat' => 'php:Y-m-d',
                    'options' => [
                        'pluginOptions' => [
                            'weekStart' => 1,
                            'autoclose' => true
                        ],
                        'layout' => '{input}{picker}',
                    ],
                ]) ?>

            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'phones')->textInput(['maxlength' => true]) ?>

            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            </div>
            <?php if (!$model->isNewRecord): ?>
            <div class="col-md-2">
                <?= $form->field($model, 'identifier')->staticControl() ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'comment')->textarea(['rows' => 3]) ?>

        </div>
        <?php if (!$model->isNewRecord): ?>
        <p>Создан <?= Yii::$app->formatter->asDate($model->created_at, 'php:d F Y в H:i') ?>.</p>
        <?php endif; ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Контрагенты', ['/counteragents'], ['class' => 'btn btn-default btn-lg', 'title' => 'Вернуться в список. Изменения не будут сохранены']) ?>

        <?php if ($model->isNewRecord): ?>
            <?= Html::submitButton('<i class="fa fa-plus-circle" aria-hidden="true"></i> Создать', ['class' => 'btn btn-success btn-lg']) ?>

        <?php else: ?>
            <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить', ['class' => 'btn btn-primary btn-lg']) ?>

        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>