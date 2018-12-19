<?php
$params = require(__DIR__ . '/params.php');
$db = file_exists(__DIR__ . '/../local/db.php') ? require(__DIR__ . '/../local/db.php') : require(__DIR__ . '/db.php');;
$envDev = file_exists(__DIR__ . '/../local/env-dev.php') ? require(__DIR__ . '/../local/env-dev.php') : YII_ENV_DEV;

$config = [
    'id' => 'maklenrc',
    'language' => 'pt-BR',
    'sourceLanguage' => 'pt-BR',
    'timeZone' => 'America/Sao_Paulo',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'Maklen RC',
    'components' => [
        'i18n' => [
            'translations' => [
                'kvgrid'=> [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@kvgrid/messages',
                    'forceTranslation' => true,
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => ['type' => 'text/javascript'],
                ],
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'LAUQ_XSYuSvr_t-EKTSxVIGSImnWAgZb',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'authTimeout' => 3600 * 12,
		],
		'session' => [
		    'timeout' => 3600 * 12,
		],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    	'urlManager' => [
   			'class' => 'yii\web\UrlManager',
   			'showScriptName' => false,
   			'enablePrettyUrl' => true,
   			/* 'rules' => array(
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
  			), */
            /* 'rules'           => [
                '<controller:[a-z-]+>/<id:\d+>'                                                   => '<controller>/view',
                '<controller:[a-z-]+>/<action:[a-z-]+>/<id:\d+>'                                  => '<controller>/<action>',
                '<controller:[a-z-]+>/<action:[a-z-]+>/<id:\d+>/<param:[a-z-]+>'                  => '<controller>/<action>',
                '<controller:[a-z-]+>/<action:[a-z-]+>'                                           => '<controller>/<action>',
            ], */
            'rules' => [
                '/' => '/site',
                '<controller:\w+>/<id:\d+>'             => '<controller>/view',
                '<controller:\w+>/<action>/<id:\d+>'    => '<controller>/<action>',
                '<controller:[\w-]+>/<action>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'         => '<controller>/<action>',
                '<controller:[\w-]+>/update/<id:\d+>'   => '<controller>/update',
                '<controller:[\w-]+>/delete/<id:\d+>'   => '<controller>/delete',
                '<controller:[\w-]+>/<id:\d+>'          => '<controller>/view',
                '<controller:[\w-]+>/<id:\d+>'          => '<controller>/detail',
                '<controller:[\w-]+>/<id:\d+>'          => '<controller>/download',
                '<controller:[\w-]+>/<id:\d+>'          => '<controller>/processar',
            ],
    	],
    ],
    'params' => $params,
    'modules' => [
        'gridview' =>  [
            'class' => 'kartik\grid\Module',
            'i18n' => [
                 'class' => 'yii\i18n\PhpMessageSource',
                 'basePath' => '@kvgrid/messages',
                 'forceTranslation' => true
            ],
        ],
    ],
    'container' => [ // configuracao default para o gridview
        'definitions' => [
            kartik\grid\GridView::class => [
                'containerOptions' => ['style' => 'overflow: auto'],
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => false,
                'bordered' => true,
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'hover' => true,
                'showPageSummary' => false,
                'toolbar' => [
                    '{toggleData}',
                ],
                'toggleDataOptions' => [
                    'all' => [
                        'icon' => 'glyphicon glyphicon-resize-full',
                        'label' => false,
                        'class' => 'btn btn-default btn-flat btn-sm',
                        'title' => 'Mostrar todos os resultados',
                        'data-toggle' => 'tooltip',
                    ],
                    'page' => [
                        'icon' => 'glyphicon glyphicon-resize-small',
                        'label' => false,
                        'class' => 'btn btn-default btn-flat btn-sm',
                        'title' => 'Mostar resultados com paginação',
                        'data-toggle' => 'tooltip',
                    ],
                ],
                'panel' => [
                    'type' => kartik\grid\GridView::TYPE_PRIMARY,
                ],
            ],
        ],
    ],
    // valida o layout conforme o usuário logado (nao implementado ainda)
    'on beforeRequest' => function ($event) {
        
    }
];

if ($envDev) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;