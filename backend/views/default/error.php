<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = 'Ошибка | '.Yii::$app->name;
$this->params['content-block'] = 'Ошибка';
$this->params['content-additional'] = $name;
?>
<div class="default-error">
    <div class="alert alert-danger">
        <?= nl2br(Html::decode($message)) ?>
    </div>

    <p>
        Произошла приведенная выше ошибка.
    </p>
    <p>
        Продолжение невозможно.
    </p>
</div>