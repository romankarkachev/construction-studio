<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use backend\models\PSTypes;

/* @var $this yii\web\View */
/* @var $model backend\models\PSSearch */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $searchApplied bool */
?>

<div class="ps-search">
    <?php $form = ActiveForm::begin([
        'action' => ['/ps'],
        'method' => 'get',
        'options' => ['id' => 'frm-search', 'class' => ($searchApplied ? 'collapse in' : 'collapse')],
    ]); ?>

    <div class="hpanel hblue">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Наименование содержит...']) ?>

                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'pst_id')->widget(Select2::className(), [
                        'data' => PSTypes::ArrayMap(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => '- выберите -'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>

                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Выполнить', ['class' => 'btn btn-info']) ?>
                <?= Html::a('Сброс', ['/ps'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>