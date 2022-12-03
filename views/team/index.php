<?php

/** @var yii\web\View $this */
/** @var app\models\Team $teams */

use app\assets\DatagridAsset;
use app\models\Team;
use yii\helpers\Url;
use app\models\User;

DatagridAsset::register($this);

$this->title = 'Teams';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- Title -->
            <div class="col-sm-6">
                <h1 class="m-0">Team Panel</h1>
            </div>
            <!-- Breadcrumb -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Team Panel</li>
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
                        <h5 class="d-inline">Team List</h5>
                        <?php if (Yii::$app->user->identity->role === User::ROLE_ADMIN) : ?>
                            <!-- Create New Team -->
                            <div class="float-right">
                                <a href="<?= Url::to(['/team/add']) ?>" class="btn bg-gradient-info btn-sm rounded-pill"><i class="fas fa-plus-circle"></i> Create New Team</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teams as $team) : ?>
                                    <tr>
                                        <!-- Title -->
                                        <td><?= $team->title ?></td>
                                        <!-- Description -->
                                        <td class="text-truncate" style="max-width: 150px;"><?= empty($team->description) ? 'N/A' : $team->description ?></td>
                                        <!-- Created At -->
                                        <td><?= date('d-M-Y', strtotime($team->created_at)) ?></td>
                                        <!-- Status -->
                                        <td class="font-weight-bold <?= $team->getStatusColor($team->status) ?>"><?= $team->getStatus($team->status) ?></td>
                                        <!-- Control -->
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                    <i class="fas fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN) : ?>
                                                        <!-- If user is Admin -->
                                                        <!-- Update -->
                                                        <a href="<?= Url::to(['/team/update', 'slug' => $team->slug]) ?>" class="dropdown-item"><i class="fas fa-pencil-alt text-info"></i> Update</a>
                                                        <!-- Change Status -->
                                                        <a class="dropdown-item" href="<?= Url::to(['/team/status', "slug" => $team->slug]) ?>">
                                                            <?= ($team->status === Team::STATUS_ACTIVE ? '<i class="fas fa-toggle-on text-info"></i>' : '<i class="fas fa-toggle-off"></i>') ?> Status
                                                        </a>
                                                    <?php endif; ?>
                                                    <!-- View User -->
                                                    <a class="dropdown-item" href="<?= Url::to(['/team/view', 'slug' => $team->slug]) ?>"><i class="far fa-eye text-info"></i> View</a>
                                                    <?php if (Yii::$app->user->identity->role == User::ROLE_SUPERADMIN || Yii::$app->user->identity->role == User::ROLE_ADMIN) : ?>
                                                        <!-- If user is Super-admin or Admin -->
                                                        <!-- Delete Team -->
                                                        <button class="dropdown-item" onclick="$('#team_slug').val(`<?= $team->slug ?>`);" data-toggle="modal" data-target="#DeleteConfirmation"><i class="fas fa-trash text-danger"></i> Delete</button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
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

<!-- Delete Team Modal -->
<div class="modal fade" id="DeleteConfirmation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="DeleteConfirmationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title" id="DeleteConfirmationLabel">Delete Team</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                <h5>Are you sure you want to delete this team?</h5>
                <form action="<?= Url::to(['/team/delete']) ?>" method="post">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                    <input type="hidden" name="team_slug" id="team_slug">
                    <div class="d-flex justify-content-center mt-4">
                        <button class="btn bg-gradient-danger rounded-pill px-5 mr-2">Yes</button>
                        <button type="button" class="btn bg-gradient-secondary rounded-pill px-5" data-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>