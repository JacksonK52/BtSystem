<?php

/** @var yii\web\View $this */
/** @var app\models\RegisterForm $model */
/** @var String $mode */

use app\models\Project;
use yii\bootstrap4\ActiveForm;
use app\models\User;
use yii\helpers\ArrayHelper;

$db = Yii::$app->getDb();
$query = $db->createCommand("
    SELECT u.* 
    FROM user u LEFT JOIN team t
    ON u.id = t.team_leader_id
    WHERE t.team_leader_id IS NULL AND u.role = 2 AND u.status = 1
");
$teamLeaderData = $query->queryAll();
?>

<?php $form = ActiveForm::begin() ?>
<!-- Project -->
<?= $form->field($model, 'project_id')->dropdownList(ArrayHelper::map(Project::find()->andWhere('status = :active', ['active' => Project::STATUS_ACTIVE])->all(), 'id', 'title'), [
    'prompt' => 'Select Project',
    'class' => 'custom-select',
])->label('Project *') ?>
<!-- Team Leader -->
<?php if ($mode == 'add') : ?>
    <?= $form->field($model, 'team_leader_id')->dropdownList(ArrayHelper::map($teamLeaderData, 'id', 'name'), [
        'prompt' => 'Select Team Leader',
        'class' => 'custom-select',
    ])->label('Team Leader *') ?>
<?php else : ?>
    <div class="form-group field-team-team_leader_id">
        <label for="team-team_leader_id">Team Leader *</label>
        <input type="text" id="team-title" class="form-control" name="Team[title]" value="<?= $model->teamLeader->name ?>" disabled>
    </div>
<?php endif; ?>
<!-- Title -->
<?= $form->field($model, 'title')->textInput(['placeholder' => 'Team Tittle', 'autofocus' => true])->label('Title *') ?>
<!-- Description -->
<?= $form->field($model, 'description')->textarea(['placeholder' => 'Team Description', 'rows' => 6])->label('Description') ?>
<!-- Form Control -->
<div class="form-group float-right">
    <button class="btn bg-gradient-primary rounded-pill px-4"><?= ($mode == 'add' ? 'Submit' : 'Update') ?></button>
</div>
<?php ActiveForm::end() ?>