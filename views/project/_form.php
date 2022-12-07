<?php

/** @var yii\web\View $this */
/** @var app\models\Project $model */
/** @var String $mode */

use yii\bootstrap4\ActiveForm;

?>

<?php $form = ActiveForm::begin() ?>
<!-- Title -->
<?= $form->field($model, 'title')->textInput(['placeholder' => 'Project Title'])->label('Title *') ?>
<!-- Description -->
<?= $form->field($model, 'description')->textarea(['placeholder' => 'Team Description', 'rows' => 6])->label('Description') ?>
<!-- Form Control -->
<div class="form-group float-right">
    <button class="btn bg-gradient-primary rounded-pill px-4"><?= ($mode == 'add' ? 'Submit' : 'Update') ?></button>
</div>
<?php ActiveForm::end() ?>