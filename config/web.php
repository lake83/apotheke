<?php
$config = [
    'id' => 'basic',
    'name' => Yii::t('main', 'Pharmacy'),
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['app\config\settings'],
    'language' => 'de-DE',
    'sourceLanguage' => 'en-US',
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'BpFsddFeU0Qaf9oYQ6Fv80iLa9ebcndv',
            'baseUrl' => ''
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'app\components\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login']
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'plugins' => [
                    [
                        'class' => 'Swift_Plugins_ThrottlerPlugin',
                        'constructArgs' => [20]
                    ]     
                ]
            ]
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer'
            ],
            'rules' => [
                ['pattern' => '<action:(contact|error|cart)>', 'route' => 'site/<action>', 'suffix' => '.html'],
                ['pattern' => '<slug>', 'route' => 'site/page', 'suffix' => '.html'],
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                '' => 'site/index',
                'admin' => 'site/admin'
            ]

        ],
        'formatter' => [
            'timeZone' => 'Europe/Berlin'
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource'
                ]
            ]
        ]
    ]
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module'
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
                'generators' => [
            'controller'   => [
                'class'     => 'yii\gii\generators\controller\Generator',
                'templates' => [
                    'actions' => '@app/components/gii/controller'
                ]
            ],
            'crud'   => [
                'class'     => 'yii\gii\generators\crud\Generator',
                'templates' => ['actions' => '@app/components/gii/crud']
            ]
        ]
    ];
}

return $config;