<?php

/** @var yii\web\View $this */
/** @var app\models\RegisterForm $model */

use yii\helpers\Url;

$param = Yii::$app->getRequest()->getQueryParam('slug');

$this->title = 'Update User';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- Title -->
            <div class="col-sm-6">
                <h1 class="m-0">Update User</h1>
            </div>
            <!-- Breadcrumb -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/user/index']) ?>">User Panel</a></li>
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/user/profile', 'slug' => $param]) ?>">Profile</a></li>
                    <li class="breadcrumb-item active">Update User</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- /.content-header -->

<!-- Main content -->
<div class="container-fluid mt-4">
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-md-10 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3>Update User Information</h3>
                    <small class="text-danger mb-0">* Field mark with astrik(*) are required</small>
                </div>
                <div class="card-body">
                    <!-- Register Form -->
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12">
                            <?= $this->render('_form', ['model' => $user, 'mode' => 'update']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>