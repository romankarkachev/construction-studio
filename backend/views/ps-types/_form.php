<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PSTypes */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="ps-types-form">
    <div class="hpanel">
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Введите наименование']) ?>

                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'name_plural_nominative_case')->textInput(['maxlength' => true, 'placeholder' => '(кто? что?)']) ?>

                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'name_plural_genitive_case')->textInput(['maxlength' => true, 'placeholder' => '(кого? чего?)']) ?>

                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'name_plural_dative_case')->textInput(['maxlength' => true, 'placeholder' => '(кому? чему?)']) ?>

                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Типы номенклатуры', ['/ps-types'], ['class' => 'btn btn-default btn-lg', 'title' => 'Вернуться в список. Изменения не будут сохранены']) ?>

        <?php if ($model->isNewRecord): ?>
            <?= Html::submitButton('<i class="fa fa-plus-circle" aria-hidden="true"></i> Создать', ['class' => 'btn btn-success btn-lg']) ?>
        <?php else: ?>
            <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить', ['class' => 'btn btn-primary btn-lg']) ?>
        <?php endif; ?>

    </div>
    <?php ActiveForm::end(); ?>

</div>