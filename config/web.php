<?php
$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'autosolutionsSystem',
    'language' => 'pt-BR',
    'sourceLanguage' => 'pt-BR',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'Auto Solutions',
    'components' => [
        'i18n' => [
            'translations' => [
                'kvgrid'=> [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'forceTranslation' => true,
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
            'enableAutoLogin' => true,
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
   			'rules' => array(
				'<controller:\w+>/<id:\d+>' => '<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
  			),
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
                        'icon' => 'resize-full',
                        'label' => false,
                        'class' => 'btn btn-default btn-flat btn-sm',
                        'title' => 'Mostrar todos os resultados',
                        'data-toggle' => 'tooltip',
                    ],
                    'page' => [
                        'icon' => 'resize-small',
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
];

if(YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;