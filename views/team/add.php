<?php

/** @var yii\web\View $this */
/** @var app\models\Team $model */

use yii\helpers\Url;

$this->title = 'New Team';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- Title -->
            <div class="col-sm-6">
                <h1 class="m-0">New Team</h1>
            </div>
            <!-- Breadcrumb -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/team/index']) ?>">Team Panel</a></li>
                    <li class="breadcrumb-item active">New Team</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- /.content-header -->

<!-- Main content -->
<div class="container-fluid mt-4">
    <div class="row justify-content-center align-items-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3>Create New Team</h3>
                    <small class="text-danger mb-0">* Field mark with astrik are manditory</small>
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