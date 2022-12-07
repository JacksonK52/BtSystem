<?php

/** @var yii\web\View $this */
/** @var app\models\Project $model */

use app\models\Project;
use app\models\User;
use yii\helpers\Url;
?>

<tr>
    <!-- Title -->
    <td><?= $project->title ?></td>
    <!-- Team Leader -->
    <td><?= (empty($project->teams[0]->teamLeader->name) ? 'N/A' : $project->teams[0]->teamLeader->name) ?></td>
    <!-- Created At -->
    <td><?= date('d-M-Y', strtotime($project->created_at)) ?></td>
    <!-- Status -->
    <td class="font-weight-bold <?= $project->getStatusColor($project->status) ?>"><?= $project->getStatus($project->status) ?></td>
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
                    <a href="<?= Url::to(['/project/update', 'slug' => $project->slug]) ?>" class="dropdown-item"><i class="fas fa-pencil-alt text-info"></i> Update</a>
                <?php endif; ?>
                <?php if (Yii::$app->user->identity->role == User::ROLE_SUPERADMIN || Yii::$app->user->identity->role == User::ROLE_ADMIN) : ?>
                    <!-- If user is Super-admin or Admin -->
                    <!-- Change Status -->
                    <a class="dropdown-item" href="<?= Url::to(['/project/change-status', "slug" => $project->slug]) ?>">
                        <?= ($project->status === Project::STATUS_ACTIVE ? '<i class="fas fa-toggle-on text-info"></i>' : '<i class="fas fa-toggle-off"></i>') ?> Status
                    </a>
                <?php endif; ?>
                <!-- View User -->
                <a class="dropdown-item" href="<?= Url::to(['/project/view', 'slug' => $project->slug]) ?>"><i class="far fa-eye text-info"></i> View</a>
                <?php if (Yii::$app->user->identity->role == User::ROLE_SUPERADMIN || Yii::$app->user->identity->role == User::ROLE_ADMIN) : ?>
                    <!-- If user is Super-admin or Admin -->
                    <!-- Delete Project -->
                    <button class="dropdown-item" onclick="$('#project_slug').val(`<?= $project->slug ?>`);" data-toggle="modal" data-target="#DeleteConfirmation"><i class="fas fa-trash text-danger"></i> Delete</button>
                <?php endif; ?>
            </div>
        </div>
    </td>
</tr>