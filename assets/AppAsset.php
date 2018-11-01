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
        'plugins/fontawesome-5.3.1/css/all.min.css',
        'plugins/jquery-confirm/jquery-confirm.css?v=1.0',
        'plugins/toastr/toastr.min.css',
        'css/site.css?v=1.0',
    ];
    public $js = [
        'plugins/jquery-confirm/jquery-confirm.js?v=1.0',
        'plugins/fontawesome-5.3.1/js/all.min.js',
        'plugins/accounting/accounting.js',
        'plugins/toastr/toastr.min.js',
        'app/js/main.js?v=1.0',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
