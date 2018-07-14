<?php
return [
    'components' => [
        'bot' => [
            'class' => \SonkoDmitry\Yii\TelegramBot\Component::class,
            'apiToken' => '650596667:AAFZNHA2_uiQIiE2k34w_di6hIVedL1Cc_M',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2_task_tracker',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
