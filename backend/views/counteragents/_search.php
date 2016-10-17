<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use backend\models\CATypes;

/* @var $this yii\web\View */
/* @var $model backend\models\CounteragentsSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $searchApplied bool */
?>

<div class="counteragents-search">
    <?php $form = ActiveForm::begin([
        'action' => ['/counteragents'],
        'method' => 'get',
        'options' => ['id' => 'frm-search', 'class' => ($searchApplied ? 'collapse in' : 'collapse')],
    ]); ?>

    <div class="hpanel hblue">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'ct_id')->widget(Select2::className(), [
                        'data' => CATypes::ArrayMap(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => '- выберите -'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>

                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'searchName')->textInput(['placeholder' => 'Наименование содержит...']) ?>

                </div>
                <div class="col-md-5">
                    <?= $form->field($model, 'searchOther')->textInput(['placeholder' => 'Контакты, примечание содержит...']) ?>

                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Выполнить', ['class' => 'btn btn-info']) ?>
                <?= Html::a('Сброс', ['/counteragents'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>