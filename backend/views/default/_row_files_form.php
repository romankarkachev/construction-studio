<?php

use yii\helpers\Html;
use backend\models\Facilities;

/* @var $this yii\web\View */
/* @var $model backend\models\DocumentsTP */
/* @var $uploadsDir string */

$iterator = 0; // счетчик объектов
foreach ($model->sharedFiles as $image) {
    // пропускаем файлы, которые не являются изображениями
    if (!Facilities::is_image($image->ffp)) continue;

    $options = ['rel' => 'fancybox'.$model->id];
    if ($iterator == 0) $options['id'] = 'fancybox-start';

    $images .= '
'.Html::a(Html::img($uploadsDir.$image->fn), $uploadsDir.$image->fn, $options);

    $iterator++;
};

$images .= newerton\fancybox\FancyBox::widget([
    'target' => 'a[rel^="fancybox"]',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '90%',
        'maxHeight' => '90%',
        'playSpeed' => 4000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);

echo $images;
