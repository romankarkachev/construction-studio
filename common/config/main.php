<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'ru-RU',
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'admins' => ['root'],
        ],
        'rbac' => 'dektrium\rbac\RbacWebModule',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'default/index',
                //'<controller:users>/<action:create>' => '/user/admin/create',
                '<action:login>' => '/user/security/login',
                '<action:logout>' => '/user/security/logout',
                '<action:users>' => '/user/admin/index',
                '<controller:users>/<action:create>' => '/user/admin/create',
                '<controller:users>/<action:update>' => '/user/admin/update',
                '<controller:users>/<action:delete>' => '/user/admin/delete',
                '<controller:users>/<action:update-profile>' => '/user/admin/update-profile',
                '<controller:users>/<action:assignments>' => '/user/admin/assignments',
                '<action:profile>' => 'user/settings/<action>',
                '<action:account>' => 'user/settings/<action>',

                'regions' => 'regions/index',
                'regions/<action:create|update|delete|list-nf>' => 'regions/<action>',
                'regions/<url:.+>' => 'regions/show',

                'facility/<identifier:.+>' => 'default/facility',

                '<controller>/<action>' => '<controller>/<action>',
            ],
        ],
    ],
];
