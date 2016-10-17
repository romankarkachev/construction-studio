<?php

use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model backend\models\PSTypes */

$this->title = $model->name.' '.HtmlPurifier::process('&mdash;').' Типы номенклатуры | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы номенклатуры', 'url' => ['/ca-types']];
$this->params['breadcrumbs'][] = $model->name;

$this->params['content-block'] = $model->name.' <a href="#manual" class="m-r-sm" data-toggle="collapse" aria-controls="manual" aria-expanded="false" title="Показать инструкцию"><i class="fa fa-question-circle"></i></a>';
$this->params['content-additional'] = 'Редактирование существующего типа номенклатуры';
?>
<div class="ps-types-update">
    <div id="manual" class="collapse text-justify">
        <div class="hpanel hyellow">
            <div class="panel-heading hbuilt">Инструкция</div>
            <div class="panel-body">
                <p>Обратите внимание, что хотя только поле &laquo;Наименование&raquo; является обязательным для заполнения, рекомендуется не оставлять пустыми все остальные поля. Например, значение поля &laquo;Множественное число родительный падеж&raquo; используется в форме документа при подсчете итоговой суммы в разрезе типов номенклатуры.</p>
                <p>Пример верного заполнения:
                    <strong><?= $model->attributeLabels()['name'] ?></strong>: Услуга,
                    <strong><?= $model->attributeLabels()['name_plural_nominative_case'] ?></strong>: Услуги,
                    <strong><?= $model->attributeLabels()['name_plural_genitive_case'] ?></strong>: Услуг,
                    <strong><?= $model->attributeLabels()['name_plural_dative_case'] ?></strong>: Услугам.
                </p>
            </div>
        </div>
    </div>

    <?= $this->render('_form', ['model' => $model,]) ?>

</div>