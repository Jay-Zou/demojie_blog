<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name' => 'Demo杰 博客',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language' => 'zh-CN',          // 配置当前语言
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\UserModel',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        // 配置国际化
        'i18n' => [
            'translations' => [
                // 所有 app 前缀的分类都指向这，可以修改为 *
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    // 'basePath' => '@app/../messages',   // 语言包路径，默认为 @app/messages 为当前目录
                    //'sourceLanguage' => 'en-US',      // 默认配置
                    'fileMap' => [
                        'common' => 'common.php',
                    ],
                ],
            ],
        ],
        // 配置 url 梅花
        'urlManager' => [
            'enablePrettyUrl' => true,  // 开启 url 美化
            'showScriptName' => false,  // 关闭脚本文件，会使用 rules 的规则
            /*'rules' => [
            ],*/
            // 'suffix' => '.html',        // 添加 .html 后缀，现在不用
        ],

    ],
    'params' => $params,
];
