<?php

use yii\bootstrap\Carousel;
use backend\models\Facilities;

/* @var $this yii\web\View */
/* @var $model backend\models\DocumentsTP */
/* @var $uploadsDir string */

$images = [];

foreach ($model->sharedFiles as $image) {
    // пропускаем файлы, которые не являются изображениями
    if (!Facilities::is_image($image->ffp)) continue;

    $images[] = [
        'content' => '<img src="'.$uploadsDir.$image->fn.'" style="display: inline;" />',
        'caption' => $image->ofn,
    ];
};
?>

<?= Carousel::widget([
    'items' => $images,
    'controls' => [
        '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>',
        '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>',
    ],
    'showIndicators' => true,
    'options'=> ['id' => 'row-images', 'class' => 'carousel slide text-center'],
    'clientOptions' => ['interval' => 5000, 'pause' => 'none', 'keyboard' => true],
]); ?>
