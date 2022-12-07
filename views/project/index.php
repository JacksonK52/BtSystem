<?php

/** @var yii\web\View $this */
/** @var app\models\Team $teams */

use app\assets\DatagridAsset;
use app\models\Project;
use yii\helpers\Url;
use app\models\User;

DatagridAsset::register($this);

$this->title = 'Project Panel';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- Title -->
            <div class="col-sm-6">
                <h1 class="m-0">Project Panel</h1>
            </div>
            <!-- Breadcrumb -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Project Panel</li>
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
                        <h5 class="d-inline">Project List</h5>
                        <?php if (Yii::$app->user->identity->role === User::ROLE_ADMIN) : ?>
                            <!-- Add Project -->
                            <div class="float-right">
                                <a href="<?= Url::to(['/project/add']) ?>" class="btn bg-gradient-info btn-sm rounded-pill"><i class="fas fa-plus-circle"></i> Add Project</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Team Leader</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Control</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (Yii::$app->user->identity->role == User::ROLE_TEAM_LEADER) : ?>
                                    <?php foreach ($projects as $project) : ?>
                                        <?php if ($project->teams[0]->team_leader_id == Yii::$app->user->getId()) : ?>
                                            <!-- If Current User role is Team-leader then display only the project assign to his team -->
                                            <?= $this->render('_list', ['project' => $project]) ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php elseif (Yii::$app->user->identity->role == User::ROLE_SUPERADMIN || Yii::$app->user->identity->role == User::ROLE_ADMIN) : ?>
                                    <!-- If Current User is Super-admin or Admin display all project -->
                                    <?php foreach ($projects as $project) : ?>
                                        <?= $this->render('_list', ['project' => $project]) ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Title</th>
                                    <th>Team Leader</th>
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

<!-- Delete Project Modal -->
<div class="modal fade" id="DeleteConfirmation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="DeleteConfirmationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title" id="DeleteConfirmationLabel">Delete Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                <h5>Are you sure you want to delete this project?</h5>
                <form action="<?= Url::to(['/project/deleted']) ?>" method="post">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                    <input type="hidden" name="project_slug" id="project_slug">
                    <div class="d-flex justify-content-center mt-4">
                        <button class="btn bg-gradient-danger rounded-pill px-5 mr-2">Yes</button>
                        <button type="button" class="btn bg-gradient-secondary rounded-pill px-5" data-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>