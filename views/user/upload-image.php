<?php

/** @var yii\web\View $this */
/** @var app\models\User $user */

use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;

$param = Yii::$app->getRequest()->getQueryParam('slug');

$this->title = 'Upload Profile Pic';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- Title -->
            <div class="col-sm-6">
                <h1 class="m-0">Upload Profile Pic</h1>
            </div>
            <!-- Breadcrumb -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/user/index']) ?>">User Panel</a></li>
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/user/profile', 'slug' => $param]) ?>">Profile</a></li>
                    <li class="breadcrumb-item active">Upload Profile Pic</li>
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
                    <h3>Profile Picture</h3>
                </div>
                <div class="card-body">
                    <!-- Register Form -->
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12">
                            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
                                <?= $form->field($user, 'img_location')->fileInput() ?>
                                <!-- Form Control -->
                                <div class="float-right">
                                    <button class="btn bg-gradient-primary rounded-pill px-4"><i class="fas fa-cloud-upload"></i> Upload</button>
                                </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>