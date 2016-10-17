<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\JsExpression;
use kartik\select2\Select2;
use kartik\file\FileInput;
use backend\models\Units;

/* @var $this yii\web\View */
/* @var $model backend\models\DocumentsTP */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $dp_files \yii\data\ActiveDataProvider */

$this->title = 'Строка документа '.$model->doc_id.' | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Объекты', 'url' => ['/facilities']];
$this->params['breadcrumbs'][] = ['label' => $model->doc->facility->name, 'url' => ['/facilities/update', 'id' => $model->doc->facility_id]];
$this->params['breadcrumbs'][] = ['label' => 'Документ '.$model->doc_id, 'url' => ['/documents/update', 'id' => $model->doc_id]];
$this->params['breadcrumbs'][] = 'Строка';

$this->params['content-block'] = 'Строка документа '.$model->doc_id;
$this->params['content-additional'] = 'Редактирование строки табличной части документа';
?>

<div class="documents-tp-form">
    <div class="form-group">
        <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Вернуться в документ', ['/documents/update', 'id' => $model->doc_id], ['class' => 'btn btn-default btn-lg', 'title' => 'Вернуться в документ. Изменения не будут сохранены']) ?>

    </div>
    <div class="hpanel">
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-5">
                    <?= $form->field($model, 'ps_id')->widget(Select2::className(), [
                        'initValueText' => $model->ps->name,
                        'theme' => Select2::THEME_BOOTSTRAP,
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
                                
                                if (result.price != "") {
                                    $("#documentstp-price").val(result.price);
                                    CalculateAmount()
                                };
                                
                                return result.text;
                            }'),
                        ],
                    ]); ?>

                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'unit_id')->widget(Select2::className(), [
                        'data' => Units::ArrayMap(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => '- выберите -'],
                    ]) ?>

                </div>
                <div class="col-md-1">
                    <?= $form->field($model, 'volume')->textInput() ?>

                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'price', ['template' => '{label}<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-rub"></span></span></div>{error}'])->textInput(['placeholder' => 'Введите число']) ?>

                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'amount', ['template' => '{label}<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-rub"></span></span></div>{error}'])->textInput(['placeholder' => 'Введите число']) ?>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'comment')->textarea() ?>

                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'comment_external')->textarea() ?>

                </div>
            </div>
            <div class="form-group">
                <?php if ($model->isNewRecord): ?>
                    <?= Html::submitButton('<i class="fa fa-plus-circle" aria-hidden="true"></i> Создать', ['class' => 'btn btn-success btn-lg']) ?>

                <?php else: ?>
                    <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить', ['class' => 'btn btn-primary btn-lg']) ?>

                <?php endif; ?>
                <?= Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i>  Удалить', ['/documents/delete-row', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-lg',
                    'data' => [
                        'confirm' => 'Вы действительно хотите удалить строку табличной части?',
                        'method' => 'post',
                    ],
                ]) ?>

            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <div class="hpanel">
        <div class="panel-heading hbuilt">
            <div class="panel-tools">
                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
            </div>
            Файлы<?= $dp_files->totalCount > 0 ? ' ('.$dp_files->totalCount.')' : '' ?>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
            <?php
                Pjax::begin(['id' => 'afs']);
                echo GridView::widget([
                    'dataProvider' => $dp_files,
                    'id' => 'gw-files',
                    'layout' => '{items}',
                    'tableOptions' => ['class' => 'table table-striped table-hover'],
                    'columns' => [
                        [
                            'attribute' => 'ofn',
                            'label' => 'Имя файла',
                            'contentOptions' => ['style' => 'vertical-align: middle;'],
                        ],
                        [
                            'attribute' => 'shared',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                /** @var \backend\models\FacilitiesFiles $model */
                                /** @var \yii\grid\DataColumn $column */
                                $attr_id = 'shared'.$model->id;
                                return '<div class="i-checks"><label for="'.$attr_id.'"><div class="icheckbox_square-green">

                  <input type="checkbox" value="1"'.($model->shared == 1 ? ' checked' : '').' id="'.$attr_id.'" data-id="'.$model->id.'" />

                </div><ins class="iCheck-helper"></ins></label></div></div>
';
                            },
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center'],
                            'options' => ['width' => '80'],
                        ],
                        [
                            'label' => 'Скачать',
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle;'],
                            'format' => 'raw',
                            'value' => function ($data) {
                                return Html::a('<i class="fa fa-cloud-download text-info" style="font-size: 18pt;"></i>', ['/documents/download-tp', 'id' => $data->id], ['title' => ($data->ofn != ''?$data->ofn.', ':'').Yii::$app->formatter->asShortSize($data->size, false), 'target' => '_blank', 'data-pjax' => 0]);
                            },
                            'options' => ['width' => '60'],
                        ],
                        [
                            'attribute' => 'uploaded_at',
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center', 'style' => 'vertical-align: middle;'],
                            'format' =>  ['date', 'dd.MM.Y HH:mm'],
                            'options' => ['width' => '130']
                        ],
                        [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Действия',
                                'template' => '{delete}',
                                'buttons' => [
                                    'delete' => function ($url, $model) {
                                        return Html::a('<i class="fa fa-trash-o"></i>', ['/documents/delete-tp-file', 'id' => $model->id], ['title' => Yii::t('yii', 'Удалить'), 'class' => 'btn btn-xs btn-danger', 'aria-label' => Yii::t('yii', 'Delete'), 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'data-pjax' => '0',]);
                                    }
                                ],
                                'options' => ['width' => '40'],
                                'headerOptions' => ['class' => 'text-center'],
                                'contentOptions' => ['class' => 'text-right', 'style' => 'vertical-align: middle;'],
                            ],
                    ],
                ]);
                Pjax::end(); ?>

                <p>Вы можете загружать до десяти файлов за один раз.</p>
                <?= FileInput::widget([
                    'id' => 'new_files',
                    'name' => 'files[]',
                    'options' => ['multiple' => true],
                    'pluginOptions' => [
                        'maxFileCount' => 10,
                        'uploadAsync' => false,
                        'uploadUrl' => Url::to(['/documents/upload-files-tp']),
                        'uploadExtraData' => [
                            'row_id' => $model->id,
                        ],
                    ]
                ]) ?>

            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Вернуться в документ', ['/documents/update', 'id' => $model->doc_id], ['class' => 'btn btn-default btn-lg', 'title' => 'Вернуться в документ. Изменения не будут сохранены']) ?>

    </div>
</div>
<?php
$url_ts = Url::to(['/documents/toggle-shared']);

$this->registerJs(<<<JS
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

// Функция-обработчик щелчка по галочке Общий доступ в списке прикрепленных файлов.
//
function ToggleSharedOnClick() {
	id = $(this).attr("data-id");
	tr = $("tr[data-key='" + id + "'");
	tr.fadeTo("fast", .5);
	$.ajax({
		type: "POST",
		url: "$url_ts",
		data: {id: id, type: 1},
		dataType: "json"
	}).always(function() {
        tr.fadeTo("fast", 1);
    });

	
	return false;
}; // ToggleSharedOnClick()

$("#new_files").on("filebatchuploadsuccess", function(event, data, previewId, index) {
    $.pjax.reload({container:"#afs"});
});

$(document).on("change", "#documentstp-volume", CalculateAmount);
$(document).on("change", "#documentstp-price", CalculateAmount);
$("[id^='shared']").on("ifChanged", ToggleSharedOnClick);
JS
, \yii\web\View::POS_READY);
?>