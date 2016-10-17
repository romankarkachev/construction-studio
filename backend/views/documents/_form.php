<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Documents */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $dp_files \yii\data\ActiveDataProvider */
/* @var $dp_table_part \yii\data\ActiveDataProvider */
/* @var $summaries array */

$summary_extended = ''; // в том числе...

$total_amount = $summaries['total_amount']; // общая сумма документа
unset($summaries['total_amount']); // общие итоги больше не нужны

if (count($summaries) > 1) {
    // перебираем массив и выводим итоговые суммы в разрезе типов номенклатуры
    foreach ($summaries as $key => $value)
        $summary_extended .= mb_strtolower($key).' на сумму <strong>'.Yii::$app->formatter->asCurrency($value).'</strong>, ';

    $summary_extended = trim($summary_extended); // обрезаем пробел в конце
    $summary_extended = '<p>В том числе '.trim($summary_extended, ',').'</p>'; // обрезаем запятую в конце
}
?>

<div class="documents-form">
    <div class="hpanel panel-collapse">
        <div class="panel-heading hbuilt">
            <div class="panel-tools">
                <a class="showhide"><i class="fa fa-chevron-down"></i></a>
            </div>
            Общие сведения
        </div>
        <div class="panel-body collapse">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="well well-lg">
                        <h5><?= $model->attributeLabels()['facility_id'] ?>: <?= $model->facility->name ?> <?= Html::a('<i class="fa fa-external-link-square" aria-hidden="true"></i>', ['/facilities/update', 'id' => $model->facility->id], ['title' => 'Открыть в новом окне', 'target' => '_blank']) ?></h5>
                        <p><?= $model->facility->attributeLabels()['name_external'] ?>: <?= $model->facility->name_external ?></p>
                        <p><?= $model->facility->attributeLabels()['address'] ?>: <?= ($model->facility->region_id != null ? ($model->facility->region->parent_id != null ? $model->facility->region->parent->name.' '.$model->facility->region->name : $model->facility->region->name).($model->facility->address != null && $model->facility->address != '' ? ', '.$model->facility->address : '') : '') ?></p>
                        <p><?= $model->facility->attributeLabels()['updated_at'] ?>: <?= Yii::$app->formatter->asDate($model->facility->updated_at, 'php:d F Y в H:i') ?></p>
                        <p><?= $model->facility->attributeLabels()['fs_id'] ?>: <?= $model->facility->state->name ?></p>
                        <?php if ($model->facility->comment != null && $model->facility->comment != ''): ?>
                        <p><strong><?= $model->facility->attributeLabels()['comment'] ?></strong></p>
                        <p class="font-trans"><?= $model->facility->comment ?></p>
                        <?php endif ?>
                        <?php if ($model->facility->comment_external != null && $model->facility->comment_external != ''): ?>
                        <p><strong><?= $model->facility->attributeLabels()['comment_external'] ?></strong></p>
                        <p class="font-trans"><?= $model->facility->comment_external ?></p>
                        <?php endif ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="well well-lg">
                        <h5><?= $model->attributeLabels()['ca_id'] ?>: <?= $model->customer->name ?> <?= Html::a('<i class="fa fa-external-link-square" aria-hidden="true"></i>', ['/counteragents/update', 'id' => $model->customer->id], ['title' => 'Открыть в новом окне', 'target' => '_blank']) ?></h5>
                        <p><?= $model->customer->attributeLabels()['name_full'] ?>: <?= $model->customer->name_full ?></p>
                        <?php if ($model->customer->phones != null && $model->customer->phones != ''): ?>
                        <p><?= $model->customer->attributeLabels()['phones'] ?>: <?= $model->customer->phones ?></p>
                        <?php endif ?>
                        <?php if ($model->customer->email != null && $model->customer->email != ''): ?>
                        <p><?= $model->customer->attributeLabels()['email'] ?>: <?= $model->customer->email ?></p>
                        <?php endif ?>
                        <p><?= $model->customer->attributeLabels()['ct_id'] ?>: <?= $model->customer->type->name ?></p>
                        <?php if ($model->customer->birthdate != null && $model->customer->birthdate != ''): ?>
                        <p><?= $model->customer->attributeLabels()['birthdate'] ?>: <?= Yii::$app->formatter->asDate($model->customer->birthdate, 'php:d F Y г.') ?></p>
                        <?php endif ?>
                        <?php if ($model->customer->comment != null && $model->customer->comment != ''): ?>
                        <p><strong><?= $model->customer->attributeLabels()['comment'] ?></strong></p>
                        <p class="font-trans"><?= nl2br($model->customer->comment) ?></p>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'total_amount', ['template'=>'{label}<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-rub"></span></span></div>{error}'])->textInput(['placeholder' => 'Введите число']) ?>

                </div>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

            </div>
            <div class="form-group">
                <?php if ($model->isNewRecord): ?>
                    <?= Html::submitButton('<i class="fa fa-plus-circle" aria-hidden="true"></i> Создать', ['class' => 'btn btn-success btn-lg']) ?>
                <?php else: ?>
                    <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить', ['class' => 'btn btn-primary btn-lg']) ?>
                <?php endif; ?>

                <?= Html::a('<i class="fa fa-trash-o" aria-hidden="true"></i> Удалить', ['/documents/delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-lg',
                    'data' => [
                        'confirm' => 'Вместе с документом удаляюся все прикрепленные файлы, в том числе к строкам табличной части и сама табличная часть. Удалить документ?',
                        'method' => 'post',
                    ],
                ]) ?>

            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
    <div class="hpanel">
        <div class="panel-heading hbuilt">
            <div class="panel-tools">
                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
            </div>
            Материалы и услуги<?= $dp_table_part->totalCount > 0 ? ' ('.$dp_table_part->totalCount.')' : '' ?>
        </div>
        <div class="panel-body">
            <?= $this->render('_add_row_to_doc_form', ['model' => $model,]) ?>

            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dp_table_part,
                    'layout' => '{items}{pager}',
                    'tableOptions' => ['class' => 'table table-striped table-hover', 'style' => 'margin-bottom: 0px;'],
                    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => '-'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'options' => ['width' => '40'],
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'psName',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                /** @var \backend\models\DocumentsTP $model */
                                /** @var \yii\grid\DataColumn $column */
                                $icon_has_comment = '';
                                $comment = '';
                                if ($model->comment != null && $model->comment != '') {
                                    $icon_has_comment = ' <i class="fa fa-commenting" aria-hidden="true" data-id="'.$model->id.'"></i>';
                                    $comment = '<div id="row-comment'.$model->id.'" class="collapse"><small class="font-trans">'.nl2br($model->comment).'</small></div>';
                                }
                                return Html::a($model->psName, ['/documents/edit-row', 'id' => $model->id]).$icon_has_comment.$comment;
                            },
                        ],
                        [
                            'attribute' => 'filesCount',
                            'label' => 'Фото',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                /** @var \backend\models\DocumentsTP $model */
                                /** @var \yii\grid\DataColumn $column */
                                $images = '<i class="fa fa-camera font-trans"></i>';
                                if ($model->filesCount > 0)
                                    $images = Html::a('<i class="fa fa-camera text-info" title="'.$model->filesCount.'"></i> <small><small>'.$model->filesCount.'</small></small>', '#modalGallery', [
                                        'class' => 'open-gallery',
                                        'data-toggle' => 'modal',
                                        'data-url' => Url::to(['/default/row-files-form', 'id' => $model->id, 'carousel' => 1]),
                                    ]);
                                return $images;
                            },
                            'options' => ['width' => '70'],
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'unitName',
                            'options' => ['width' => '120'],
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'volume',
                            'format' => 'decimal',
                            'options' => ['width' => '80'],
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'price',
                            'format' => 'decimal',
                            'options' => ['width' => '80'],
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'amount',
                            'format' => 'decimal',
                            'options' => ['width' => '120'],
                            'headerOptions' => ['class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-right'],
                        ],
                        // принято решение использовать для редактирования ссылку в наименовании номенклатуры
//                        [
//                            'class' => 'yii\grid\ActionColumn',
//                            'header' => 'Действия',
//                            'template' => '{update}',
//                            'buttons' => [
//                                'update' => function ($url, $model) {
//                                    return Html::a('<i class="fa fa-pencil"></i>', ['/documents/edit', 'id' => $model->id], ['title' => Yii::t('yii', 'Редактировать'), 'class' => 'btn btn-xs btn-default']);
//                                },
//                            ],
//                            'options' => ['width' => '80'],
//                            'headerOptions' => ['class' => 'text-center'],
//                            'contentOptions' => ['class' => 'text-right'],
//                        ],
                    ],
                ]); ?>

            </div>
            <div class="panel-footer">
                <p class="lead text-right" style="margin-bottom: 10px;">
                    Итого: <strong><?= Yii::$app->formatter->asCurrency($total_amount) ?></strong>
                </p>
                <?= $summary_extended ?>

            </div>
        </div>
    </div>
    <div class="hpanel panel-collapse">
        <div class="panel-heading hbuilt">
            <div class="panel-tools">
                <a class="showhide"><i class="fa fa-chevron-down"></i></a>
            </div>
            Файлы<?= $dp_files->totalCount > 0 ? ' ('.$dp_files->totalCount.')' : '' ?>
        </div>
        <div class="panel-body collapse">
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
                                return Html::a('<i class="fa fa-cloud-download text-info" style="font-size: 18pt;"></i>', ['/documents/download', 'id' => $data->id], ['title' => ($data->ofn != ''?$data->ofn.', ':'').Yii::$app->formatter->asShortSize($data->size, false), 'target' => '_blank', 'data-pjax' => 0]);
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
                                    return Html::a('<i class="fa fa-trash-o"></i>', ['/documents/delete-file', 'id' => $model->id], ['title' => Yii::t('yii', 'Удалить'), 'class' => 'btn btn-xs btn-danger', 'aria-label' => Yii::t('yii', 'Delete'), 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'data-pjax' => '0',]);
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
                        'uploadUrl' => Url::to(['/documents/upload-files']),
                        'uploadExtraData' => [
                            'doc_id' => $model->id,
                        ],
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGallery" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="color-line"></div>
            <div class="modal-header">
                <h4 class="modal-title">Файлы</h4>
                <small class="font-bold">Просмотр прикрепленных файлов.</small>
            </div>
            <div id="rff-content" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<?php
$url_ts = Url::to(['/documents/toggle-shared']);

$this->registerJs(<<<JS
// Функция-обработчик щелчка по ссылке Комментарий к строке в списке строк документа.
//
function ToggleRowComment() {
    data_id = $(this).attr("data-id");
    $("#row-comment" + data_id).toggle();
    
    return false;
}; // ToggleRowComment()

// Функция-обработчик щелчка по ссылке Открыть галерею.
//
function OpenGalleryOnClick() {
    $("#rff-content").html("<div class=\"text-center\"><img id=\"preloader\" src=\"/images/preloader.gif\" /></div>");
    value = $(this).attr("data-url");
    $("#rff-content").load(value);
    
    return false;
}; // OpenGalleryOnClick()

// Функция-обработчик щелчка по галочке Общий доступ в списке прикрепленных файлов.
//
function ToggleSharedOnClick() {
	id = $(this).attr("data-id");
	tr = $("tr[data-key='" + id + "'");
	tr.fadeTo("fast", .5);
	$.ajax({
		type: "POST",
		url: "$url_ts",
		data: {id: id, type: 0},
		dataType: "json"
	}).always(function() {
        tr.fadeTo("fast", 1);
    });

	
	return false;
}; // ToggleSharedOnClick()

$("#new_files").on("filebatchuploadsuccess", function(event, data, previewId, index) {
    $.pjax.reload({container:"#afs"});
});

$(document).on("click", ".fa-commenting", ToggleRowComment);
$(document).on("click", "a.open-gallery", OpenGalleryOnClick);
$("[id^='shared']").on("ifChanged", ToggleSharedOnClick);
JS
, \yii\web\View::POS_READY);
?>