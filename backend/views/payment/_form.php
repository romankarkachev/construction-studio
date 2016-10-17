<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;
use kartik\select2\Select2;
use backend\models\PaymentMethods;
use backend\models\Currencies;
use backend\models\Facilities;

/* @var $this yii\web\View */
/* @var $model backend\models\Payment */
/* @var $form yii\bootstrap\ActiveForm */

$currencies = Currencies::ArrayMapForSelect2();

//var_dump($currencies);
//var_dump(Currencies::ArrayMap());
?>

<div class="payment-form">
    <div class="hpanel">
        <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'pm_id')->widget(Select2::className(), [
                        'data' => PaymentMethods::ArrayMap(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => '- выберите -'],
                    ]) ?>

                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'currency_id')->widget(Select2::className(), [
                        'data' => $currencies['arrayMap'],
//                        'data' => $currencies,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => [
                            'placeholder' => '- выберите -',
                            'options' => $currencies['extraData']
                        ],
                        'pluginOptions' => [
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(result) { return result.text; }'),
                            'templateSelection' => new JsExpression('function (result) { return result.text; }'),
                        ],
                        'pluginEvents' => [
                            "change" => new JsExpression('function() {
                                $("#currency-full_name").text($("#payment-currency_id option:selected").attr("data-nf"));
                                $("#currency-full_name").show();
                            }'),
                        ],
                    ]) ?>

                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'amount', ['template'=>'{label}<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-rub"></span></span></div>{error}'])->textInput(['placeholder' => 'Введите число']) ?>

                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'ca_id')->widget(Select2::className(), [
                        'initValueText' => $model->counteragent->name,
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
                <div class="col-md-3">
                    <?= $form->field($model, 'facility_id')->widget(Select2::className(), [
                        'data' => Facilities::ArrayMap(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => '- выберите -'],
                    ]) ?>

                </div>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'comment')->textarea(['rows' => 3]) ?>

            </div>
            <p id="currency-full_name" class="text-muted<?= $model->isNewRecord ? ' collapse' : '' ?>"><?= $model->isNewRecord ? '' : $model->currency->name_full ?></p>
        </div>
    </div>
    <div class="form-group">
        <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Оплата', ['/payment'], ['class' => 'btn btn-default btn-lg', 'title' => 'Вернуться в список. Изменения не будут сохранены']) ?>
        <?php if ($model->isNewRecord): ?>
            <?= Html::submitButton('<i class="fa fa-plus-circle" aria-hidden="true"></i> Создать', ['class' => 'btn btn-success btn-lg']) ?>
        <?php else: ?>
            <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить', ['class' => 'btn btn-primary btn-lg']) ?>
        <?php endif; ?>
    </div>
        <?php ActiveForm::end(); ?>

</div>