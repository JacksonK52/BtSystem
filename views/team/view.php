<?php

/** @var yii\web\View $this */
/** @var app\models\Team $team */
/** @var app\models\TeamMember $team_members */
/** @var app\models\User app\models\TeamMember $available_members */

use app\models\TeamMember;
use app\models\Team;
use yii\helpers\Url;
use app\models\User;
use yii\widgets\ActiveForm;

$this->title = 'View Team';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- Title -->
            <div class="col-sm-6">
                <h1 class="m-0">View Team</h1>
            </div>
            <!-- Breadcrumb -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/team/index']) ?>">Team Panel</a></li>
                    <li class="breadcrumb-item active">View Team</li>
                </ol>
            </div>
        </div>
    </div>
</div><!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Team Information -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header bg-gradient-info">
                        <h4>Team Information</h4>
                    </div>
                    <div class="card-body">
                        <!-- Team Title -->
                        <p class="font-weight-bold mb-0"><i class="fas fa-users"></i> Title:</p>
                        <p class="text-muted mb-4"><?= $team->title ?></p>

                        <!-- Description -->
                        <p class="font-weight-bold mb-0"><i class="fas fa-book-reader"></i> Description:</p>
                        <p class="text-muted mb-4"><?= (empty($team->description) ? 'N/A' : $team->description) ?></p>

                        <!-- Team Leader -->
                        <p class="font-weight-bold mb-0"><i class="fas fa-user"></i> Team Leader:</p>
                        <p class="text-muted mb-4"><?= (empty($team->teamLeader->name) ? 'Data Not Found' : $team->teamLeader->name) ?></p>

                        <!-- Status -->
                        <p class="font-weight-bold mb-0"><i class="<?= $team->status == Team::STATUS_ACTIVE ? 'fas fa-toggle-on' : 'fas fa-toggle-off' ?>"></i> Status:</p>
                        <p class="text-muted mb-4"><?= $team->getStatus($team->status) ?></p>

                        <!-- Updated By -->
                        <p class="font-weight-bold mb-0"><i class="fas fa-user-clock"></i> Last Updated By:</p>
                        <p class="text-muted mb-4"><?= (empty($team->updatedBy->name) ? 'N/A' : $team->updatedBy->name) ?></p>

                        <!-- Created By -->
                        <p class="font-weight-bold mb-0"><i class="fas fa-user-clock"></i> Created By:</p>
                        <p class="text-muted mb-0"><?= (empty($team->createdBy->name) ? 'N/A' : $team->createdBy->name) ?></p>
                    </div>
                    <div class="card-footer">
                        <small>
                            <?= (empty($team->updated_at) ?
                                'Created: ' . date('d-M-Y', strtotime($team->created_at)) :
                                'Last Update: ' . date('d-M-Y', strtotime($team->updated_at))
                            ) ?>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Team Member Information -->
            <div class="col-12 col-lg-8">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-md-8">
                                <h4>Team Members Information</h4>
                            </div>
                            <?php if ($team->status == Team::STATUS_ACTIVE) : ?>
                                <div class="col-12 col-md-4 text-right">
                                    <button class="btn bg-gradient-primary rounded-pill btn-sm" data-toggle="modal" data-target="#AddTeamMember"><i class="fas fa-plus-circle"></i> Add Team Member</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="min-height: calc(100vh - 260px); max-height: calc(100vh - 260px);">
                            <table class="table table-head-fixed text-nowrap table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Control</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($team_members as $teamMember) : ?>
                                        <tr>
                                            <!-- Name -->
                                            <td><?= (empty($teamMember->user->name) ? 'Data Not Found!' : $teamMember->user->name) ?></td>
                                            <!-- Role -->
                                            <td><?= (empty($teamMember->user->role) ? 'Data Not Found!' : ($teamMember->user->role === User::ROLE_DEVELOPER ? 'Developer' : ($teamMember->user->role === User::ROLE_TESTER ? 'Tester' : 'Data Not Found!'))) ?></td>
                                            <!-- Status -->
                                            <td class="font-weight-bold <?= $teamMember->getStatusColor($teamMember->status) ?>"><?= $teamMember->getStatus($teamMember->status) ?></td>
                                            <!-- Control -->
                                            <td>
                                                <button type="button" class="btn bg-gradient-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <?php if ($team->status == Team::STATUS_ACTIVE) : ?>
                                                        <!-- If team status is active -->
                                                        <!-- Change Status -->
                                                        <a class="dropdown-item" href="<?= Url::to(['/team/change-status', 'slug' => Yii::$app->getRequest()->getQueryParam('slug'), 'id' => $teamMember->id]) ?>">
                                                            <?= ($teamMember->status === Team::STATUS_ACTIVE ? '<i class="fas fa-toggle-on text-info"></i>' : '<i class="fas fa-toggle-off"></i>') ?> Status
                                                        </a>
                                                        <!-- Delete Team -->
                                                        <button class="dropdown-item" onclick="$('#team_id').val(`<?= $teamMember->id ?>`); $('#team_slug').val(`<?= $team->slug ?>`);" data-toggle="modal" data-target="#DeleteConfirmation"><i class="fas fa-trash text-danger"></i> Remove</button>
                                                    <?php else : ?>
                                                        <!-- If team status is not active -->
                                                        <p class="dropdown-item mb-0">Team is Inactive</p>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
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

<!-- Add Team Member Modal -->
<div class="modal fade" id="AddTeamMember" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="AddTeamMemberLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-gradient-primary">
                <h5 class="modal-title" id="AddTeamMemberLabel">Add Team Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['action' => '/team/add-team-member']) ?>
                <!-- Slug -->
                <input type="hidden" name="teamSlug" value="<?= Yii::$app->getRequest()->getQueryParam('slug') ?>">
                <input type="hidden" name="teamLeader" value="<?= $team->id ?>">
                <!-- Team Title -->
                <div class="form-group">
                    <label for="">Team Title *</label>
                    <input type="text" class="form-control" value="<?= $team->title ?>" disabled>
                </div>
                <!-- Team Member -->
                <div class="form-group">
                    <label for="selectMember">Select Team Member *</label>
                    <select class="form-control" name="selectMember" id="selectMember">
                        <?php if (count($users) > 0) : ?>
                            <?php foreach ($users as $user) : ?>
                                <option value="<?= $user['id'] ?>"><?= "{$user['name']}" ?></option>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <option value="" selected>No User Available</option>
                        <?php endif; ?>
                    </select>
                </div>
                <!-- Form Control -->
                <div class="float-right mt-4">
                    <button class="btn bg-gradient-primary rounded-pill px-5 mr-2" <?= (count($users) == 0 ? 'disabled' : '') ?>>Add</button>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Team Modal -->
<div class="modal fade" id="DeleteConfirmation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="DeleteConfirmationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title" id="DeleteConfirmationLabel">Remove Team Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                <h5>Are you sure you want to remove this team member?</h5>
                <form action="<?= Url::to(['/team/remove']) ?>" method="post">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                    <input type="hidden" name="team_id" id="team_id">
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