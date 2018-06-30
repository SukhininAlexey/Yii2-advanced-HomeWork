<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' =>true,
            'rules' => [
                '/' => 'site/index',
                
                // Календарь в двух режимах: индекс и отображение конкретного таска
                'calendar' => 'task/index',
                'calendar/<taskId:\d+>' => 'task/single',
                
                // Все возможные режимы работы модулей
                '<module:\w+>' => '<module>/',
                '<module:\w+>/<controller:\w+>/<id:\d+>' => '<module>/<controller>',
                '<module:\w+>/<controller:\w+>' => '<module>/<controller>', // Правило под вопросом, т.к. нигде не используется
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                
                // Наиболее вероятные без модулей
                '<controller>/<action><lang:\w+>' => '<controller>/<action>',
                '<controller>/<action>' => '<controller>/<action>',
                '<controller>' => '<controller>/index',
            ],
        ],
    ],
];
