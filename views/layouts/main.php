<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use yii\bootstrap4\Html;

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <!-- Canonical -->
    <link rel="canonical" href="<?= Yii::$app->request->hostInfo ?>" />
    <!-- FavIcon -->
    <link rel="shortcut icon" href="/default/Logo.png" type="image/x-icon">
    <?php $this->head() ?>
</head>

<body class="sidebar-mini layout-fixed sidebar-collapse">
    <?php $this->beginBody() ?>

    <div class="wrapper">
        <!-- Admin Navbar -->
        <?= $this->render('_navbar', ['controller' => $controller, 'action' => $action]); ?>
        <!-- Admin Sidebar -->
        <?= $this->render('_sidebar', ['controller' => $controller, 'action' => $action]); ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?= $content ?>
        </div><!-- /.content-wrapper -->
        <!-- Admin Footer -->
        <?= $this->render('_footer', ['controller' => $controller, 'action' => $action]); ?>
    </div><!-- /.wrapper -->

    <?php $this->endBody() ?>

    <!-- Notification System -->
    <script>
        // JS Notification
        const showToastr = (key, message) => {
            if (key === 'success') {
                toastr.success(message);
            } else if (key === 'info') {
                toastr.info(message);
            } else if (key === 'warning') {
                toastr.warning(message);
            } else {
                toastr.error(message);
            }
        }
        // PHP Notification
        <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message) : ?>
            showToastr('<?= $key ?>', '<?= $message ?>');
        <?php endforeach; ?>
    </script>
</body>

</html>
<?php $this->endPage() ?>