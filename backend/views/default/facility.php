<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model \backend\models\Facilities */
/* @var $dp_tp \yii\data\ActiveDataProvider */
/* @var $summaries array */

$this->title = 'Объект | '.Yii::$app->name;
$this->params['breadcrumbs'][] = $model->name_external;

$this->params['content-block'] = $model->name_external;
$this->params['content-additional'] = 'Просмотр выполненных работ и примененных материалов. Последнее обновление '.Yii::$app->formatter->asDate($model->created_at, 'php:d F Y в H:i').'.';
?>

<div class="hpanel">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <h4>Объект <small><?= $model->id ?></small></h4>
            </div>
        </div>
    </div>
    <div class="panel-body p-xl">
        <div class="row m-b-xl">
            <div class="col-sm-6">
                <h4><?= $model->identifier ?></h4>

                <address>
                    <strong><?= $model->name_external ?></strong><br>
                    <?= ($model->region->parent == null ? '' : $model->region->parent->name).' '.$model->region->name ?><br>
                    <?= $model->address ?><br>
                </address>
            </div>
            <div class="col-sm-6 text-right">
                <span><?= $model->customer->identifier ?></span>
                <address>
                    <strong><?= $model->customer->name_full ?></strong><br>
                    <?= $model->customer->email ?><br>
                    <abbr title="Телефон">Тел:</abbr> <?= $model->customer->phones ?>
                </address>
            </div>
        </div>

        <div class="table-responsive m-t">
            <?= GridView::widget([
                'dataProvider' => $dp_tp,
                'layout' => '{items}{pager}',
                'tableOptions' => ['class' => 'table table-striped', 'style' => 'margin-bottom: 0px;'],
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
                            $name = $model->psName;
                            $comment = '';
                            if ($model->comment_external != null && $model->comment_external != '') {
                                $name = '<strong>'.$model->psName.'</strong>';
                                $comment = '<p class="font-trans" style="margin-bottom: 0px;"><small>'.nl2br($model->comment_external).'</small></p>';
                            }
                            return $name.$comment;
                        },
                    ],
                    [
                        'attribute' => 'filesCount',
                        'label' => 'Файлы',
                        'format' => 'raw',
                        'value' => function ($model, $key, $index, $column) {
                            /** @var \backend\models\DocumentsTP $model */
                            /** @var \yii\grid\DataColumn $column */
                            $images = '<i class="fa fa-camera font-trans"></i>';
                            //if ($model->filesCount > 0) $images = '<i class="fa fa-camera text-info"></i> <small><small>'.$model->filesCount.'</small></small>';
                            if ($model->filesCount > 0)
                                $images = Html::a('<i class="fa fa-camera text-info" title="'.$model->filesCount.'"></i>', '#modalGallery', [
                                    'class' => 'open-gallery',
                                    'data-toggle' => 'modal',
                                    'data-url' => Url::to(['/default/row-files-form', 'id' => $model->id]),
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
                ],
            ]); ?>

        </div>

        <div class="row m-t">
            <div class="col-md-4 col-md-offset-8">
                <?php
                $summary_extended = ''; // в том числе...

                $total_amount = $summaries['total_amount']; // общая сумма документа
                unset($summaries['total_amount']); // общие итоги больше не нужны

                if (count($summaries) > 1) {
                    // перебираем массив и выводим итоговые суммы в разрезе типов номенклатуры
                    foreach ($summaries as $key => $value)
                        $summary_extended .= '
                    <tr>
                      <td><strong>в т.ч. '.mb_strtolower($key).':</strong></td>
                      <td>'.Yii::$app->formatter->asCurrency($value).'</td>
                    </tr>';
                }
                ?>
                <table class="table table-striped text-right">
                    <tbody>
                    <tr class="lead font-extra-bold">
                        <td>Общий итог:</td>
                        <td><?= Yii::$app->formatter->asCurrency($total_amount) ?></td>
                    </tr>
                    <?= $summary_extended ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if ($model->comment_external != null && $model->comment_external != ''): ?>
        <div class="row">
            <div class="col-md-6">
                <div class="m-t"><strong>Комментарий</strong>
                    <p><?= nl2br($model->comment_external) ?></p>

                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div id="images" class="modal fade"></div>
<?php
$this->registerJs(<<<JS
// Функция-обработчик щелчка по ссылке Открыть галерею.
//
function OpenGalleryOnClick() {
    value = $(this).attr("data-url");
    $("#images").load(value, function() {
        $("#fancybox-start").click();
    });
    
    return false;
}; // OpenGalleryOnClick()

$(document).on("click", "a.open-gallery", OpenGalleryOnClick);
JS
, \yii\web\View::POS_READY);
?>