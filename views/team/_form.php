<?php

/** @var yii\web\View $this */
/** @var app\models\RegisterForm $model */
/** @var String $mode */

use yii\bootstrap4\ActiveForm;
use app\models\User;
use yii\helpers\ArrayHelper;

?>

<?php $form = ActiveForm::begin() ?>
<!-- Team Leader -->
<?= $form->field($model, 'team_leader_id')->dropdownList(ArrayHelper::map(User::find()->andWhere('role = :teamLeader and status = :active', ['teamLeader' => User::ROLE_TEAM_LEADER, 'active' => User::STATUS_ACTIVE])->all(), 'id', 'name'), [
    'prompt' => 'Select Team Leader',
    'class' => 'custom-select',
])->label('Team Leader *') ?>
<!-- Title -->
<?= $form->field($model, 'title')->textInput(['placeholder' => 'Team Tittle', 'autofocus' => true])->label('Title *') ?>
<!-- Description -->
<?= $form->field($model, 'description')->textarea(['placeholder' => 'Team Description', 'rows' => 6])->label('Description') ?>
<!-- Form Control -->
<div class="form-group float-right">
    <button class="btn bg-gradient-primary rounded-pill px-4"><?= ($mode == 'add' ? 'Submit' : 'Update') ?></button>
</div>
<?php ActiveForm::end() ?>