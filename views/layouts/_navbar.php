<?php

/** @var $this yii\web\View */
/** @var $controller app\controller\id */
/** @var $action app\controller\action\id */

use yii\helpers\Url;

?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav ">
        <!-- Menu Button -->
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="<?= Url::to(['/site']) ?>" role="button">Dashboard</a>
        </li>
        <!-- Log out -->
        <?php if (!Yii::$app->user->isGuest) : ?>
            <li class="nav-item d-none d-sm-inline-block">
                <form class="form-inline" action="<?= Url::to('/site/logout') ?>" method="post">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                    <button type="submit" class="btn nav-link">Log Out</button>
                </form>
            </li>
        <?php endif; ?>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item dropdown-footer">Not Implemented Yet</a>
            </div>
        </li>

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">Not Implemented Yet</span>
            </div>
        </li>

        <!-- Expands -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->