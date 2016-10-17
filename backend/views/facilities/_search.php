<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;
use kartik\select2\Select2;
use backend\models\FacilitiesStates;

/* @var $this yii\web\View */
/* @var $model backend\models\FacilitiesSearch */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $searchApplied bool */
?>

<div class="facilities-search">
    <?php $form = ActiveForm::begin([
        'action' => ['/facilities'],
        'method' => 'get',
        'options' => ['id' => 'frm-search', 'class' => ($searchApplied ? 'collapse in' : 'collapse')],
    ]); ?>

    <div class="hpanel hblue">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'fs_id')->widget(Select2::className(), [
                        'data' => FacilitiesStates::ArrayMap(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => '- выберите -'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>

                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'customer_id')->widget(Select2::className(), [
                        'initValueText' => $model->customer->name,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'language' => 'ru',
                        'options' => ['placeholder' => 'Введите наименование'],
                        'pluginOptions' => [
                            'allowClear' => true,
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
                <div class="col-md-5">
                    <?= $form->field($model, 'searchCommon')->textInput(['placeholder' => 'Поиск по всем полям']) ?>

                </div>
    <?php // echo $form->field($model, 'fs_id') ?>

    <?php // echo $form->field($model, 'region_id') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'customer_id') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'comment_external') ?>
            </div>
        <div class="form-group">
            <?= Html::submitButton('Выполнить', ['class' => 'btn btn-info']) ?>
            <?= Html::a('Сброс', ['/facilities'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>