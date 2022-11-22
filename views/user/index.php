<?php

/** @var yii\web\View $this */
/** @var app\models\User $users */

use app\assets\DatagridAsset;
use yii\helpers\Url;

DatagridAsset::register($this);

$this->title = 'Users';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- Title -->
            <div class="col-sm-6">
                <h1 class="m-0">User Panel</h1>
            </div>
            <!-- Breadcrumb -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">User Panel</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h5 class="d-inline">Users List</h5>
                        <div class="float-right">
                            <a href="<?= Url::to(['/user/add']) ?>" class="btn bg-gradient-info btn-sm rounded-pill"><i class="fas fa-plus-circle"></i> Register New User</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user) : ?>
                                    <tr>
                                        <!-- Name -->
                                        <td><?= $user->name ?></td>
                                        <!-- Email -->
                                        <td><?= $user->email ?></td>
                                        <!-- Role -->
                                        <td><?= $user->getRole($user->role) ?></td>
                                        <!-- Status -->
                                        <td class="font-weight-bold <?= $user->getStatusColor($user->status) ?>"><?= $user->getStatus($user->status) ?></td>
                                        <!-- Control -->
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <i class="fas fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Control</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /.content -->

<!-- Delete Category Modal -->
<div class="modal fade" id="DeleteConfirmation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="DeleteConfirmationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title" id="DeleteConfirmationLabel">Delete Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                <h5>Are you sure you want to delete this category?</h5>
                <form action="<?= Url::to(['/categories/delete']) ?>" method="post">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                    <input type="hidden" name="delete_model" id="delete_model">
                    <input type="hidden" name="delete_category" id="delete_category">
                    <div class="d-flex justify-content-center mt-4">
                        <button class="btn bg-gradient-danger rounded-pill px-5 mr-2">Yes</button>
                        <button type="button" class="btn bg-gradient-secondary rounded-pill px-5" data-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>