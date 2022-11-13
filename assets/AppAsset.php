<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // Google Font: Source Sans Pro
        'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback',
        // Font Awesome 5.15.4 Pro
        'plugins/fontawesome/css/all.css',
        // Font Awesome Animation
        'plugins/fontawesome-animation/font-awesome-animation.min.css',
        // Notification Toastr
        'plugins/adminlte/css/toastr.min.css',
        // AdminLTE 3.2
        'plugins/adminlte/css/adminlte.min.css',
        // Custom Css
        'css/site.css',
    ];
    public $js = [
        // Bootstrap 4
        'plugins/adminlte/js/bootstrap.bundle.min.js',
        // Notification Toastr
        'plugins/adminlte/js/toastr.min.js',
        // AdminLTE 3.2
        'plugins/adminlte/js/adminlte.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
