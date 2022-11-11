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
class BackendAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // Google Font: Source Sans Pro (AdminLTE Template Required)
        'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback',
        // Font Awesome 5.15.4 Pro
        'css/fontawesome/css/all.css',
        // DataGrid
        'css/datagrid/dataTables.bootstrap4.min.css',
        'css/datagrid/responsive.bootstrap4.min.css',
        'css/datagrid/buttons.bootstrap4.min.css',
        // Notification Toastr
        'css/theme/toastr.min.css',
        // AdminLTE 3.2
        'css/theme/adminlte.min.css',
        // Custom Backend Css
        'css/backend.css',
    ];
    public $js = [
        // Bootstrap 4
        'js/theme/bootstrap.bundle.min.js',
        // DataGrid
        'js/datagrid/jquery.dataTables.min.js',
        'js/datagrid/dataTables.bootstrap4.min.js',
        'js/datagrid/dataTables.responsive.min.js',
        'js/datagrid/responsive.bootstrap4.min.js',
        'js/datagrid/dataTables.buttons.min.js',
        'js/datagrid/buttons.bootstrap4.min.js',
        'js/datagrid/jszip.min.js',
        'js/datagrid/pdfmake.min.js',
        'js/datagrid/vfs_fonts.js',
        'js/datagrid/buttons.html5.min.js',
        'js/datagrid/buttons.print.min.js',
        'js/datagrid/buttons.colVis.min.js',
        'js/datagrid/custDataGrid.js',
        // Notification Toastr
        'js/theme/toastr.min.js',
        // AdminLTE 3.2
        'js/theme/adminlte.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
