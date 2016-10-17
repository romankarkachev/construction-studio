<?php

/* @var $this yii\web\View */
/* @var $model backend\models\Documents */

$this->title = 'Новый документ | '.Yii::$app->name;
$this->params['breadcrumbs'][] = ['label' => 'Объекты', 'url' => ['/facilities']];
$this->params['breadcrumbs'][] = ['label' => $model->facility->name, 'url' => ['/facilities/update', 'id' => $model->facility_id]];
$this->params['breadcrumbs'][] = 'Новый документ *';

$this->params['content-block'] = 'Новый документ <a href="#manual" class="m-r-sm" data-toggle="collapse" aria-controls="manual" aria-expanded="false" title="Показать инструкцию"><i class="fa fa-question-circle"></i></a>';
$this->params['content-additional'] = 'Создание нового документа';
?>
<div class="documents-create">
    <div id="manual" class="collapse text-justify">
        <div class="hpanel hyellow">
            <div class="panel-heading hbuilt">Инструкция</div>
            <div class="panel-body">
                <p>Документ создается с уже заполненными некоторыми данными - Объектом и Заказчиком. Добавление строк табличной части, загрузка файлов и другие возможности станут доступны только после записи нового документа в базу данных.</p>
            </div>
        </div>
    </div>

    <?= $this->render('_creation_form', ['model' => $model]) ?>

</div>