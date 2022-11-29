<?php

/** @var yii\web\View $this */
/** @var app\models\User $users */

use app\assets\DatagridAsset;
use yii\helpers\Url;

DatagridAsset::register($this);

$this->title = 'Register Account';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- Title -->
            <div class="col-sm-6">
                <h1 class="m-0">Register Account</h1>
            </div>
            <!-- Breadcrumb -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/user/index']) ?>">User Panel</a></li>
                    <li class="breadcrumb-item active">Register Account</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- /.content-header -->

<!-- Main content -->
<div class="container-fluid">
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3>Registration</h3>
                    <p class="text-danger mb-0">* Field mark with astrik are manditory</p>
                    <p class="text-danger mb-0">* Password should be minimum of 6 character long</p>
                </div>
                <div class="card-body">
                    <!-- Register Form -->
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12">
                            <?= $this->render('_form', ['model' => $model, 'mode' => 'user-registration']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>