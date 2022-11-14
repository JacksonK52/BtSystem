<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

$this->title = 'Reset Password';
?>

<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-12 col-md-8 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <?php $form = ActiveForm::begin() ?>
                        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'password'])->label('Password *') ?>
                        <?= $form->field($model, 'confirm_password')->passwordInput(['placeholder' => 'password'])->label('Confirm Password *') ?>
                        <div class="form-group float-right">
                            <button class="btn bg-gradient-primary rounded-pill">Change Password</button>
                        </div>
                    <?php ActiveForm::end() ?>
                </div>
                <div class="card-footer text-center py-0">
                    <small>Developed by Jackson Konjengbam & Gobinda Deb</small>
                </div>
            </div>
        </div>
    </div>
</div>