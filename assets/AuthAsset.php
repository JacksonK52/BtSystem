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
class AuthAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // Google Font: Source Sans Pro (AdminLTE Template Required)
        'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback',
        // Font Awesome 5.15.4 Pro
        'css/fontawesome/css/all.css',
        // Font Awesome Animation
        'css/fontawesome-animation/font-awesome-animation.min.css',
        // Notification Toastr
        'css/theme/toastr.min.css',
        // AdminLTE 3.2
        'css/theme/adminlte.min.css',
        // Custom Auth Css
        'css/auth/auth.css'
    ];
    public $js = [
        // Bootstrap 4
        'js/theme/bootstrap.bundle.min.js',
        // Notification Toastr
        'js/theme/toastr.min.js',
        // AdminLTE 3.2
        'js/theme/adminlte.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
