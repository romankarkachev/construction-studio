<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use backend\models\DocumentsTP;
use backend\models\Units;

/* @var $model backend\models\Documents */

$frm_row = ActiveForm::begin([
    'action' => '/documents/add-row'
]);
$new_row = new DocumentsTP();
$new_row->doc_id = $model->id;
?>
<?= $frm_row->field($new_row, 'doc_id')->hiddenInput()->label(false); ?>

<div class="hpanel">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-5">
                <?= $frm_row->field($new_row, 'ps_id')->widget(Select2::className(), [
                    'initValueText' => $new_row->ps->name,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'size' => Select2::SMALL,
                    'language' => 'ru',
                    'options' => ['placeholder' => 'Введите наименование'],
                    'pluginOptions' => [
                        'minimumInputLength' => 1,
                        'language' => 'ru',
                        'ajax' => [
                            'url' => Url::to(['ps/list-nr']),
                            'delay' => 250,
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(result) { return result.text; }'),
                        'templateSelection' => new JsExpression('function (result) {
                            // при загрузке страницы тоже выполняется, но отсутствуют дополнительные реквизиты,
                            // например unit_id
                            if (result.unit_id == undefined) return result.text;
                            $("#documentstp-unit_id").select2("val", result.unit_id);
                            
                            if (result.price != "") $("#documentstp-price").val(result.price);
                            
                            return result.text;
                        }'),
                    ],
                ]); ?>

            </div>
            <div class="col-md-2">
                <?= $frm_row->field($new_row, 'unit_id')->widget(Select2::className(), [
                    'data' => Units::ArrayMap(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'size' => Select2::SMALL,
                    'options' => ['placeholder' => '- выберите -'],
                ]) ?>

            </div>
            <div class="col-md-1">
                <?= $frm_row->field($new_row, 'volume')->textInput(['class' => 'form-control input-sm']) ?>

            </div>
            <div class="col-md-2">
                <?= $frm_row->field($new_row, 'price', ['template' => '{label}<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-rub"></span></span></div>{error}'])->textInput(['placeholder' => 'Введите число', 'class' => 'form-control input-sm']) ?>

            </div>
            <div class="col-md-2">
                <?= $frm_row->field($new_row, 'amount', ['template' => '{label}<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-rub"></span></span></div>{error}'])->textInput(['placeholder' => 'Введите число', 'class' => 'form-control input-sm']) ?>

            </div>
        </div>
        <p>
            <?= Html::a($new_row->attributeLabels()['comments'], '#', ['id' => 'toggle-comment', 'class' => 'link-ajax']) ?>

        </p>
        <div id="block-new-row-comment" class="row collapse">
            <div class="col-md-6">
                <?= $frm_row->field($new_row, 'comment')->textarea() ?>

            </div>
            <div class="col-md-6">
                <?= $frm_row->field($new_row, 'comment_external')->textarea() ?>

            </div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('<i class="fa fa-plus" aria-hidden="true"></i> Добавить', ['class' => 'btn btn-success']) ?>

        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
<?php
$this->registerJs(<<<JS
// Функция-обработчик щелчка по ссылке Примечание.
// Показывает блок с примечанием к строке табличной части.
//
function ToggleComment() {
    $(this).hide();
    $("#block-new-row-comment").show();
    
    return false;
}; // ToggleComment()

// Функция-обработчик изменения значения в реквизитах Объем и Цена.
// Производится автоматический пересчет суммы как произведения значений этих полей.
//
function CalculateAmount() {
	volume = parseFloat($("#documentstp-volume").val());
	price = parseFloat($("#documentstp-price").val());
	if (!isNaN(volume) && !isNaN(price)) {
		amount = volume * price;
		amount = amount.toFixed(2);
		
		$("#documentstp-amount").val(amount);
	};
}; // CalculateAmount()

$(document).on("change", "#documentstp-volume", CalculateAmount);
$(document).on("change", "#documentstp-price", CalculateAmount);
$(document).on("click", "#toggle-comment", ToggleComment);
JS
, \yii\web\View::POS_READY);
?>