<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Thiago You <thya9o@outlook.com>
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/fontawesome-5.0.12/css/fontawesome-all.min.css',
        'plugins/jquery-confirm/jquery-confirm.min.css',
        'plugins/toastr/toastr.min.css',
    ];
    public $js = [
        'plugins/jquery-confirm/jquery-confirm.min.js',
        'plugins/toastr/toastr.min.js',
        'app/js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
