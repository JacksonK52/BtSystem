<?php

/** @var yii\web\View $this */
/** @var app\models\RegisterForm $model */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\User;

if(Yii::$app->user->identity->role == User::ROLE_SUPERADMIN) {
    $roleArray = array(
        ['id' => 1, 'title' => 'Admin']
    );
} else {
    $roleArray = array(
        ['id' => 2, 'title' => 'Team Leader'],
        ['id' => 3, 'title' => 'Tester'],
        ['id' => 4, 'title' => 'Developer']
    );
}
$roleData = ArrayHelper::map($roleArray, 'id', 'title');
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<?php if ($mode == 'update') : ?>
    <!-- Name -->
    <div class="form-row">
        <div class="col-12">
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Full Name'])->label('Name *') ?>
        </div>
    </div>
<?php else : ?>
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
    <!-- User Role & Img Location -->
    <div class="form-row">
        <?php if ($mode == 'user-registration') : ?>
            <!-- User Role -->
            <div class="col-12 col-md-6">
                <?= $form->field($model, 'role')->dropdownList($roleData, [])->label('Role *') ?>
            </div>
        <?php endif; ?>
        <!-- Img Location -->
        <div class="col-12 col-md-6">
            <?= $form->field($model, 'img_location')->fileInput() ?>
        </div>
    </div>
<?php endif; ?>
<!-- Buttons -->
<div class="form-group float-right">
    <?php if ($mode == 'admin-registration') : ?>
        <!-- If mode is admin-registration show login button -->
        <a class="btn btn-outline-info rounded-pill" href="<?= Url::to(['/site']) ?>">Login</a>
    <?php endif; ?>
    <button class="btn bg-gradient-primary rounded-pill"><?= ($mode == 'update' ? 'Update' : 'Register') ?></button>
</div>
<?php ActiveForm::end() ?>