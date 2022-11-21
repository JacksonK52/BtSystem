<?php

/** @var yii\web\View $this */
/** @var app\models\RegisterForm $model */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin() ?>
<!-- Name & Email -->
<div class="form-row">
    <!-- Name -->
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'name')->textInput(['placeholder' => 'Full Name'])->label('Name *') ?>
    </div>
    <!-- Email -->
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'inputmode' => 'email'])->label('Email *') ?>
    </div>
</div>
<!-- Password & Confirm Password -->
<div class="form-row">
    <!-- Password -->
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label('Password *') ?>
    </div>
    <!-- Confirm Password -->
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'confirm_password')->passwordInput(['placeholder' => 'Confirm Password'])->label('Confirm Password *') ?>
    </div>
</div>
<!-- Img Location -->
<?= $form->field($model, 'img_location')->fileInput() ?>
<div class="form-group float-right">
    <a class="btn btn-outline-info rounded-pill" href="<?= Url::to(['/site']) ?>">Login</a>
    <button class="btn bg-gradient-primary rounded-pill">Register</button>
</div>
<?php ActiveForm::end() ?>