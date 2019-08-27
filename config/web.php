<?php
$config = [
    'id' => 'basic',
    'name' => 'Apotheke',
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
            'loginUrl' => ['site/admin']
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.sendgrid.net',
                'username' => 'lake0362',
                'password' => 'poiwao9i',
                'port' => '587',
                'encryption' => 'tls'
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
                ['pattern' => '<action:(contact|error|reviews)>', 'route' => 'site/<action>', 'suffix' => '.html'],
                ['pattern' => '<action:(cart|order)>', 'route' => 'shop/<action>', 'suffix' => '.html'],
                ['pattern' => '<slug>', 'route' => 'site/page', 'suffix' => '.html'],
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                '' => 'site/index',
                'admin' => 'site/admin'
            ]
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        YII_DEBUG ? 'jquery.js' : 'jquery.min.js'
                    ]
                ],
                'yii\jui\JuiAsset' => [
                    'js' => [
                        YII_DEBUG ? 'jquery-ui.js' : 'jquery-ui.min.js'
                    ],
                    'css' => [
                        YII_DEBUG ? 'themes/smoothness/jquery-ui.css' : 'themes/smoothness/jquery-ui.min.css'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        YII_DEBUG ? 'css/bootstrap.css' : 'css/bootstrap.min.css'
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        YII_DEBUG ? 'js/bootstrap.js' : 'js/bootstrap.min.js'
                    ]
                ],
                'app\assets\AppAsset' => [
                    'css' => [
                        YII_DEBUG ? 'css/site.css' : 'css/site.min.css'
                    ]
                ],
                'app\assets\AdminAsset' => [
                    'js' => [
                        YII_DEBUG ? 'js/admin.js' : 'js/admin.min.js'
                    ]
                ]
            ]
        ],
        'formatter' => [
            'timeZone' => 'Europe/Berlin',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'EUR'        
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