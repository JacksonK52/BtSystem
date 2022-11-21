<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

$this->registerCssFile("@web/css/auth/forgotpassword.css", ['depends' => \app\assets\AuthAsset::class]);
$this->registerJsFile("@web/js/auth/forgotpassword.js", ['depends' => \app\assets\AuthAsset::class]);

$this->title = 'Forgot Password';
?>

<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-12 col-md-8 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3>Search Account</h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin() ?>
                        <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'inputmode' => 'email'])->label('Email *') ?>
                        <div class="form-group float-right">
                            <a href="<?= Url::to(['/site/login']) ?>" class="btn btn-outline-info rounded-pill mr-2">Login</a>
                            <button class="btn bg-gradient-primary rounded-pill">Change Password</button>
                        </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>