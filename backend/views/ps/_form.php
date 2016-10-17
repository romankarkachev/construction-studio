<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use backend\models\PSTypes;
use backend\models\Units;

/* @var $this yii\web\View */
/* @var $model backend\models\PS */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="ps-form">
    <div class="hpanel">
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Введите наименование']) ?>

                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'name_full')->textInput(['maxlength' => true, 'placeholder' => 'Введите полное наименование']) ?>

                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'pst_id')->widget(Select2::className(), [
                        'data' => PSTypes::ArrayMap(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => '- выберите -'],
                    ]) ?>

                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'bu_id')->widget(Select2::className(), [
                        'data' => Units::ArrayMap(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => '- выберите -'],
                    ]) ?>

                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'price', ['template'=>'{label}<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-rub"></span></span></div>{error}'])->textInput(['placeholder' => 'Введите число']) ?>

                </div>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'comment')->textarea(['rows' => 3]) ?>

            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Номенклатура', ['/ps'], ['class' => 'btn btn-default btn-lg', 'title' => 'Вернуться в список. Изменения не будут сохранены']) ?>
        <?php if ($model->isNewRecord): ?>
            <?= Html::submitButton('<i class="fa fa-plus-circle" aria-hidden="true"></i> Создать', ['class' => 'btn btn-success btn-lg']) ?>
        <?php else: ?>
            <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить', ['class' => 'btn btn-primary btn-lg']) ?>
        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs(<<<JS
// Функция-обработчик изменения значения в поле Наименование.
//
function NameOnChange() {
    name_full = $("#ps-name_full").val();
    if (name_full == "") $("#ps-name_full").val($(this).val());
    
    return false;
}; // NameOnChange()

$(document).on("change", "#ps-name", NameOnChange);
JS
, \yii\web\View::POS_READY);
?>