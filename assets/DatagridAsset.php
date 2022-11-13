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
class DatagridAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // DataGrid
        'plugins/datagrid/css/dataTables.bootstrap4.min.css',
        'plugins/datagrid/css/responsive.bootstrap4.min.css',
        'plugins/datagrid/css/buttons.bootstrap4.min.css',
    ];
    public $js = [
        // DataGrid
        'plugins/datagrid/js/jquery.dataTables.min.js',
        'plugins/datagrid/js/dataTables.bootstrap4.min.js',
        'plugins/datagrid/js/dataTables.responsive.min.js',
        'plugins/datagrid/js/responsive.bootstrap4.min.js',
        'plugins/datagrid/js/dataTables.buttons.min.js',
        'plugins/datagrid/js/buttons.bootstrap4.min.js',
        'plugins/datagrid/js/jszip.min.js',
        'plugins/datagrid/js/pdfmake.min.js',
        'plugins/datagrid/js/vfs_fonts.js',
        'plugins/datagrid/js/buttons.html5.min.js',
        'plugins/datagrid/js/buttons.print.min.js',
        'plugins/datagrid/js/buttons.colVis.min.js',
        'plugins/datagrid/js/custDataGrid.js',
    ];
    public $depends = [
        \app\assets\BackendAsset::class,
    ];
}
