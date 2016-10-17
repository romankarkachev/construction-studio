<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;
use kartik\select2\Select2;
use kartik\file\FileInput;
use backend\models\FacilitiesStates;

/* @var $this yii\web\View */
/* @var $model backend\models\Facilities */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $documents \yii\data\ActiveDataProvider */
/* @var $files \yii\data\ActiveDataProvider */
?>

<div class="facilities-form">
    <div class="form-group">
        <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Объекты', ['/facilities'], ['class' => 'btn btn-default btn-lg', 'title' => 'Вернуться в список. Изменения не будут сохранены']) ?>

    </div>
    <div class="hpanel">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#general"> Общие сведения</a></li>
            <li><a data-toggle="tab" href="#documents">Документы<?= $documents->totalCount > 0 ? ' ('.$documents->totalCount.')': '' ?></a></li>
            <li><a data-toggle="tab" href="#files">Файлы<?= $files->totalCount > 0 ? ' ('.$files->totalCount.')' : '' ?></a></li>
        </ul>
        <div class="tab-content">
            <div id="general" class="tab-pane active">
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'name_external')->textInput(['maxlength' => true]) ?>

                        </div>
                        <div class="col-md-2">
                            <?= $form->field($model, 'identifier')->staticControl() ?>

                        </div>
                        <div class="col-md-2">
                            <div class="form-group field-facilities-updated_at">
                                <label class="control-label" for="facilities-updated_at">Обновлен</label>
                                <p class="form-control-static"><?= Yii::$app->formatter->asDate($model->updated_at, 'php:d F Y в H:i') ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?= $form->field($model, 'fs_id')->widget(Select2::className(), [
                                'data' => FacilitiesStates::ArrayMap(),
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'options' => ['placeholder' => '- выберите -'],
                            ]) ?>

                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'region_id')->widget(Select2::className(), [
                                'initValueText' => $model->region->name,
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'language' => 'ru',
                                'options' => ['placeholder' => 'Введите наименование'],
                                'pluginOptions' => [
                                    'minimumInputLength' => 3,
                                    'language' => 'ru',
                                    'ajax' => [
                                        'url' => Url::to(['/regions/list-nf']),
                                        'delay' => 250,
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(result) { return result.text; }'),
                                    'templateSelection' => new JsExpression('function (result) { return result.text; }'),
                                ]
                            ]); ?>

                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?= $form->field($model, 'customer_id')->widget(Select2::className(), [
                                'initValueText' => $model->customer->name,
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
                                    'templateSelection' => new JsExpression('function (result) {
                                        // при загрузке страницы тоже выполняется, но отсутствуют дополнительные реквизиты,
                                        // например name_full
                                        if (result.name_full == undefined) return result.text;
                                        $("#customer-full-name").html(result.name_full + " <span id=\"customer-type\" class=\"label label-info\">" + result.ca_type + "</span>");
                                        
                                        if (result.phones == "")
                                            $("#customer-phones").html("<p class=\"font-trans\">отсутствует</p>");
                                        else
                                            $("#customer-phones").text(result.phones);
                                        
                                        if (result.email == "")
                                            $("#customer-email").html("<p class=\"font-trans\">отсутствует</p>");
                                        else
                                            $("#customer-email").text(result.email);
                                        
                                        if (result.comment == "")
                                            $("#customer-comment").html("<p class=\"font-trans\">отсутствует</p>");
                                        else
                                            $("#customer-comment").html(result.comment.replace(/([^>])\n/g, \'$1<br/>\'));
                                        
                                        return result.text;
                                    }'),
                                ],
                                'pluginEvents' => [
                                    'change' => new JsExpression('function() {
                                        $("#customer-details").show("fast");
                                    }'),
                                ]
                            ]); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <div id="customer-details" class="well well-lg<?= $model->isNewRecord ? ' collapse' : '' ?>">
                            <dl>
                                <dd id="customer-full-name"><?= $model->isNewRecord || $model->customer->name_full == '' ? '<p class="font-trans">отсутствует</p>' : $model->customer->name_full ?> <span id="customer-type" class="label label-info"><?= $model->isNewRecord || $model->customer->type->name == '' ? '<p class="font-trans">отсутствует</p>' : $model->customer->type->name ?></span></dd>
                                <dt>Номера телефонов</dt>
                                <dd id="customer-phones"><?= $model->isNewRecord || $model->customer->phones == '' ? '<p class="font-trans">отсутствует</p>' : $model->customer->phones ?></dd>
                                <dt>E-mail</dt>
                                <dd id="customer-email"><?= $model->isNewRecord || $model->customer->email == '' ? '<p class="font-trans">отсутствует</p>' : $model->customer->email ?></dd>
                                <dt>Примечание</dt>
                                <dd id="customer-comment" class="text-muted"><?= $model->isNewRecord || $model->customer->comment == '' ? '<p class="font-trans">отсутствует</p>' : nl2br($model->customer->comment) ?></dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'comment')->textarea(['rows' => 3]) ?>

                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'comment_external')->textarea(['rows' => 3]) ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить', ['class' => 'btn btn-primary btn-lg']) ?>

                        <?= Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i> Удалить', ['/facilities/delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger btn-lg',
                            'data' => [
                                'confirm' => 'Вместе с объектом удаляюся все прикрепленные данные (файлы к объекту, документам, строкам табличных частей, сами документы). Удалить объект?',
                                'method' => 'post',
                            ],
                        ]) ?>

                    </div>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
            <div id="documents" class="tab-pane">
                <div class="panel-body">
                    <p>
                        <?= Html::a('<i class="fa fa-plus-circle"></i> Создать', ['/documents/create', 'f_id' => $model->id], ['class' => 'btn btn-success']) ?>

                    </p>

                    <?= $this->render('_documents', [
                        'dataProvider' => $documents,
                    ]); ?>

                </div>
            </div>
            <div id="files" class="tab-pane">
                <div class="panel-body">
                    <?= $this->render('_files', [
                        'dataProvider' => $files,
                    ]); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="hpanel">
        <div class="panel-heading hbuilt">
            <div class="panel-tools">
                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
            </div>
            Загрузить файлы
        </div>
        <div class="panel-body">
            <?= FileInput::widget([
                'id' => 'new_files',
                'name' => 'files[]',
                'options' => ['multiple' => true],
                'pluginOptions' => [
                    'maxFileCount' => 10,
                    'uploadAsync' => false,
                    'uploadUrl' => Url::to(['/facilities/upload-files']),
                    'uploadExtraData' => [
                        'facility_id' => $model->id,
                    ],
                ]
            ]) ?>

        </div>
    </div>
    <div class="form-group">
        <?= Html::a('<i class="fa fa-arrow-left" aria-hidden="true"></i> Объекты', ['/facilities'], ['class' => 'btn btn-default btn-lg', 'title' => 'Вернуться в список. Изменения не будут сохранены']) ?>

    </div>
</div>
<?php
$url_ts = Url::to(['/facilities/toggle-shared']);

// функция ToggleSharedOnClick применяется для элементов, генерируемых в представлении _files.php

$this->registerJs(<<<JS
$("#new_files").on("filebatchuploadsuccess", function(event, data, previewId, index) {
    $.pjax.reload({container:"#afs"});
});

// Функция-обработчик щелчка по галочке Общий доступ в списке прикрепленных файлов.
//
function ToggleSharedOnClick() {
	id = $(this).attr("data-id");
	tr = $("tr[data-key='" + id + "'");
	tr.fadeTo("fast", .5);
	$.ajax({
		type: "POST",
		url: "$url_ts",
		data: {id: id},
		dataType: "json"
	}).always(function() {
        tr.fadeTo("fast", 1);
    });

	
	return false;
}; // ToggleSharedOnClick()

$("[id^='shared']").on("ifChanged", ToggleSharedOnClick);
JS
, \yii\web\View::POS_READY);
?>