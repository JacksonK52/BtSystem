<?php

/** @var yii\web\View $this */
/** @var app\models\User $users */

use app\assets\DatagridAsset;
use yii\helpers\Url;
use app\models\User;

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
                        <?php if (Yii::$app->user->identity->role === User::ROLE_ADMIN || Yii::$app->user->identity->role === User::ROLE_TEAM_LEADER) : ?>
                            <!-- User Registration -->
                            <div class="float-right">
                                <a href="<?= Url::to(['/user/add']) ?>" class="btn bg-gradient-info btn-sm rounded-pill"><i class="fas fa-plus-circle"></i> Register New User</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created At</th>
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
                                        <!-- Created At -->
                                        <td><?= date('d-M-Y', strtotime($user->created_at)) ?></td>
                                        <!-- Status -->
                                        <td class="font-weight-bold <?= $user->getStatusColor($user->status) ?>"><?= $user->getStatus($user->status) ?></td>
                                        <!-- Control -->
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <i class="fas fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Change Status -->
                                                    <a class="dropdown-item" href="<?= Url::to(['/user/change-status', "slug" => $user->slug]) ?>">
                                                        <?= ($user->status === User::STATUS_ACTIVE ? '<i class="fas fa-toggle-on text-info"></i>' : '<i class="fas fa-toggle-off"></i>') ?> Status
                                                    </a>
                                                    <!-- View User -->
                                                    <a class="dropdown-item" href="<?= Url::to(['/user/profile', 'slug' => $user->slug]) ?>"><i class="far fa-eye text-info"></i> View</a>
                                                    <?php if (Yii::$app->user->identity->role != User::ROLE_DEVELOPER || Yii::$app->user->identity->role != User::ROLE_TESTER) : ?>
                                                        <!-- Delete User -->
                                                        <button class="dropdown-item" onclick="$('#user_slug').val(`<?= $user->slug ?>`);" data-toggle="modal" data-target="#DeleteConfirmation"><i class="fas fa-trash text-danger"></i> Delete</button>
                                                    <?php endif; ?>
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
                                    <th>Created At</th>
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

<!-- Delete User Modal -->
<div class="modal fade" id="DeleteConfirmation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="DeleteConfirmationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title" id="DeleteConfirmationLabel">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                <h5>Are you sure you want to delete this user?</h5>
                <form action="<?= Url::to(['/user/delete']) ?>" method="post">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                    <input type="hidden" name="user_slug" id="user_slug">
                    <div class="d-flex justify-content-center mt-4">
                        <button class="btn bg-gradient-danger rounded-pill px-5 mr-2">Yes</button>
                        <button type="button" class="btn bg-gradient-secondary rounded-pill px-5" data-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>