<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use backend\models\Regions;

/* @var $this yii\web\View */
/* @var $model backend\models\Regions */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $parent_model backend\models\Regions */
?>

<div class="regions-form">
    <div class="hpanel">
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'parent_id')->widget(Select2::className(), [
                        'data' => Regions::ArrayMapRegions(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => '- выберите -'],
                    ]) ?>

                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Регионы', ['/regions'], ['class' => 'btn btn-default btn-lg', 'title' => 'Вернуться в список. Изменения не будут сохранены']) ?>
        <?php if ($parent_model !== null): ?>
        <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> '.$parent_model->name, ['/regions/'.$parent_model->id], ['class' => 'btn btn-default btn-lg', 'title' => 'Вернуться в список. Изменения не будут сохранены']) ?>
        <?php endif; ?>
        <?php if ($model->isNewRecord): ?>
            <?= Html::submitButton('<i class="fa fa-plus-circle" aria-hidden="true"></i> Создать', ['class' => 'btn btn-success btn-lg']) ?>
        <?php else: ?>
            <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить', ['class' => 'btn btn-primary btn-lg']) ?>
        <?php endif; ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>