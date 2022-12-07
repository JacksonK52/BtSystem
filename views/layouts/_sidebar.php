<?php

/** @var $this yii\web\View */
/** @var $controller app\controller\id */
/** @var $action app\controller\action\id */

use yii\helpers\Url;
use app\models\User;

?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-light-info overflow-hidden">
    <!-- Brand Logo -->
    <a href="<?= Url::to(['/site']) ?>" class="brand-link bg-light">
        <img src="/default/Logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text text-dark font-weight-bold"><?= Yii::$app->name ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <!-- Profile Picture & User Name -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <!-- User profile picture -->
            <div class="image">
                <img src="<?= empty(Yii::$app->user->identity->img_location) ? '/default/user.png' : Yii::$app->user->identity->img_location ?>" class="img-circle elevation-2" alt="User">
            </div>
            <!-- User name -->
            <div class="info">
                <a href="<?= Url::to(['/user/profile']) ?>" class="d-block"><?= Yii::$app->user->identity->name ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <!-- ========= Dashboard ========= -->
                <li class="nav-item <?= ($controller == 'site' && ($action == 'index' || $action == 'features')) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= ($controller == 'site' && ($action == 'index' || $action == 'features')) ? 'active' : '' ?>">
                        <!-- Dropdown Function -->
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>
                            Dashboard
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- All Features -->
                        <li class="nav-item">
                            <a href="<?= Url::to(['/site/features']) ?>" class="nav-link <?= (($controller == 'site' && $action == 'features') ? 'active' : '') ?>">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>All Features</p>
                            </a>
                        </li>
                        <!-- Favorite -->
                        <li class="nav-item">
                            <a href="<?= Url::to(['/site']) ?>" class="nav-link <?= (($controller == 'site' && $action == 'index') ? 'active' : '') ?>">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Favorite</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ========= Project Panel ========= -->
                <?php if (Yii::$app->user->identity->role !== User::ROLE_DEVELOPER || Yii::$app->user->identity->role !== User::ROLE_TESTER) : ?>
                    <li class="nav-item <?= ($controller == 'project') ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link <?= ($controller == 'project') ? 'active' : '' ?>">
                            <!-- Dropdown Function -->
                            <i class="nav-icon fas fa-disc-drive"></i>
                            <p>
                                Project
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <!-- Project List -->
                            <li class="nav-item">
                                <a href="<?= Url::to(['/project/index']) ?>" class="nav-link <?= (($controller == 'project' && ($action == 'index' || $action == 'add' || $action == 'update' || $action == 'view')) ? 'active' : '') ?>">
                                    <i class="far fa-circle nav-icon text-danger"></i>
                                    <p>Project List</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                <?php endif; ?>

                <!-- ========= Teams Panel ========= -->
                <?php if (Yii::$app->user->identity->role !== User::ROLE_DEVELOPER || Yii::$app->user->identity->role !== User::ROLE_TESTER) : ?>
                    <li class="nav-item <?= ($controller == 'team') ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link <?= ($controller == 'team') ? 'active' : '' ?>">
                            <!-- Dropdown Function -->
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Team
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <!-- Team List -->
                            <li class="nav-item">
                                <a href="<?= Url::to(['/team/index']) ?>" class="nav-link <?= (($controller == 'team' && ($action == 'index' || $action == 'add' || $action == 'update' || $action == 'view')) ? 'active' : '') ?>">
                                    <i class="far fa-circle nav-icon text-danger"></i>
                                    <p>Team List</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                <?php endif; ?>

                <!-- ========= Users Panel ========= -->
                <li class="nav-item <?= ($controller == 'user' || $controller == 'profile') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= ($controller == 'user' || $controller == 'profile') ? 'active' : '' ?>">
                        <!-- Dropdown Function -->
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>
                            Users
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Profile -->
                        <li class="nav-item">
                            <a href="<?= Url::to(['/user/profile']) ?>" class="nav-link <?= ((($controller == 'user' && ($action == 'profile' || $action == 'update' || $action == 'upload-image')) || ($controller == 'profile' && ($action == 'add' || $action == 'update'))) ? 'active' : '') ?>">
                                <i class="far fa-circle nav-icon text-danger"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <!-- User List -->
                        <?php if (Yii::$app->user->identity->role === User::ROLE_SUPERADMIN || Yii::$app->user->identity->role === User::ROLE_ADMIN) : ?>
                            <li class="nav-item">
                                <a href="<?= Url::to(['/user/index']) ?>" class="nav-link <?= (($controller == 'user' && ($action == 'index' || $action == 'add')) ? 'active' : '') ?>">
                                    <i class="far fa-circle nav-icon text-danger"></i>
                                    <p>User List</p>
                                </a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </li>

                <!-- ========= Logout ========= -->
                <?php if (!Yii::$app->user->isGuest) : ?>
                    <li class="nav-item d-md-none">
                        <form class="form-inline d-lg-none" action="<?= Url::to('/site/logout') ?>" method="post">
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                            <button type="submit" class="btn app-form-button btn-block"><i class="nav-icon fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>