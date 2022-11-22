<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AuthAsset;
use yii\bootstrap4\Html;

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

AuthAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <?php $this->registerCsrfMetaTags() ?>
    <!-- Title -->
    <title><?= Html::encode($this->title) ?></title>
    <!-- FavIcon -->
    <link rel="shortcut icon" href="/default/Logo.png" type="image/x-icon">

    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <?= $content ?>

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