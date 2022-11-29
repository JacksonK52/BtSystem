<?php

/** @var yii\web\View $this */
/** @var app\models\Profile $profile */

use yii\helpers\Url;

$param = Yii::$app->getRequest()->getQueryParam('slug');

$this->title = 'Additional Information';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- Title -->
            <div class="col-sm-6">
                <h1 class="m-0">Additional Information</h1>
            </div>
            <!-- Breadcrumb -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/user/index']) ?>">User Panel</a></li>
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/user/profile', 'slug' => $param]) ?>">Profile</a></li>
                    <li class="breadcrumb-item active">Additional Information</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- /.content-header -->

<!-- Main content -->
<div class="container-fluid mt-4">
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3>Add Additional Information Form</h3>
                </div>
                <div class="card-body">
                    <!-- Register Form -->
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12">
                            <?= $this->render('_form', ['model' => $model, 'mode' => 'add']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>