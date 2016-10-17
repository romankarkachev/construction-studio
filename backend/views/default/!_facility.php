<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model \backend\models\Facilities */
/* @var $dp_tp \yii\data\ActiveDataProvider */

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
            <!--
            <div class="col-md-6">
                <div class="text-right">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-dollar"></i> Make A Payment</button>
                </div>

            </div>
            -->
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
                <!--
                <p>
                    <span><strong>Invoice Date:</strong> May 16, 2016</span><br/>
                </p>
                -->
            </div>
        </div>

        <p>74 кабря 2015 г.</p>
        <div class="table-responsive m-t">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Номенклатура</th>
                    <th>Ед. изм.</th>
                    <th>Объем</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div><strong>Lorem Ipsum is that it has a more-or-less normal</strong></div>
                        <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore.
                        </small>
                    </td>
                    <td>1</td>
                    <td>$26.00</td>
                    <td>$5.98</td>
                    <td>$31,98</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <h5 class="text-right">Итого: 7 220,00 р.</h5>
        </div>

        <p>21 октября 2015 г.</p>
        <div class="table-responsive m-t">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Номенклатура</th>
                    <th>Ед. изм.</th>
                    <th>Объем</th>
                    <th>Цена</th>
                    <th>Сумма</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div><strong>Lorem Ipsum is that it has a more-or-less normal</strong></div>
                        <small>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor incididunt ut labore.
                        </small>
                    </td>
                    <td>1</td>
                    <td>$26.00</td>
                    <td>$5.98</td>
                    <td>$31,98</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <h5 class="text-right">Итого: 7 220,00 р.</h5>
        </div>

        <div class="row m-t">
            <div class="col-md-4 col-md-offset-8">
                <table class="table table-striped text-right">
                    <tbody>
                    <tr class="lead font-extra-bold">
                        <td>Общий итог:</td>
                        <td>$1026.00</td>
                    </tr>
                    <tr>
                        <td><strong>в т.ч. материалы:</strong></td>
                        <td>$235.98</td>
                    </tr>
                    <tr>
                        <td><strong>в т.ч. работы:</strong></td>
                        <td>$1261.98</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="m-t"><strong>Комментарий</strong>
                    <p><?= nl2br($model->comment_external) ?></p>

                </div>
            </div>
        </div>

    </div>
</div>
