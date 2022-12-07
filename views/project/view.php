<?php

/** @var yii\web\View $this */
/** @var app\models\Project $project */

use app\models\Team;
use yii\helpers\Url;
use app\models\User;

$teams = Team::find()->andWhere('project_id = :pId and status != :deleted', ['pId' => $project->id, 'deleted' => Team::STATUS_DELETED])->all();

$this->title = 'View Project';
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
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/project/index']) ?>">Project Panel</a></li>
                    <li class="breadcrumb-item active">View Project</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Project Information -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header bg-gradient-info">
                        <h5>Project Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- Title -->
                        <strong>Title:</strong>
                        <p class="text-muted"><?= $project->title ?></p>
                        <!-- Description -->
                        <strong>Description:</strong>
                        <p class="text-muted"><?= $project->description ?></p>
                        <!-- Status -->
                        <strong>Status:</strong>
                        <p class="text-muted"><?= $project->getStatus($project->status) ?></p>
                        <!-- Last Updated By -->
                        <strong>Last Updated By:</strong>
                        <p class="text-muted"><?= $project->updatedBy->name ?></p>
                        <!-- Created By -->
                        <strong>Created By:</strong>
                        <p class="text-muted"><?= $project->createdBy->name ?></p>
                    </div>
                    <div class="card-footer">
                        <small><?= (empty($project->updated_at) ? "Created At: " . date('d_m-Y', strtotime($project->created_at)) : "Last Updated: " . date('d_m-Y', strtotime($project->updated_at))) ?></small>
                    </div>
                </div>
            </div>
            <!-- Assign Team List -->
            <div class="col-12 col-lg-8">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-md-8">
                                <h4>Assign Team Information</h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="min-height: calc(100vh - 260px); max-height: calc(100vh - 260px);">
                            <table class="table table-head-fixed text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Team Leader</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($teams as $team) : ?>
                                        <tr>
                                            <!-- Title -->
                                            <td><?= $team->title ?></td>
                                            <!-- Description -->
                                            <td class="text-truncate" style="max-width: 150px;"><?= (empty($team->description) ? 'N/A' : $team->description) ?></td>
                                            <!-- Team Leader -->
                                            <td><?= (empty($team->teamLeader->name) ? 'N/A' : $team->teamLeader->name) ?></td>
                                            <!-- Status -->
                                            <td class="font-weight-bold <?= $team->getStatusColor($team->status) ?>"><?= $team->getStatus($team->status) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>