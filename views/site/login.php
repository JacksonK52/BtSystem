<?php

/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use app\assets\ParticlejsAsset;

ParticlejsAsset::register($this);

$this->title = 'Login';
?>

<div class="div-full-screen" id="particles-js">
    <canvas class="particles-js-canvas-el" style="width: 100vw; height: 100vh;"></canvas>
</div>
<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-12 col-md-8 col-lg-4">
            <!-- /.login-logo -->
            <div class="card glassmorphism">
                <div class="card-body rounded pb-0">
                    <div class="row justify-content-center">
                        <div class="col-12 text-center">
                            <h2 class="app-font-michroma text-white heading"><?= Yii::$app->name ?></h2>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <?php $form = ActiveForm::begin() ?>
                            <!-- Email -->
                            <div class="form-row">
                                <div class="col-12">
                                    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'inputmode' => 'email'])->label('Email', ['class' => 'text-white']) ?>
                                </div>
                            </div>
                            <!-- Password -->
                            <div class="form-row">
                                <div class="col-12">
                                    <?= $form->field($model, 'password')->passwordInput(["placeholder" => "Password"])->label("Password", ["class" => "text-white"]) ?>
                                </div>
                            </div>
                            <!-- Forgot password & Submit btn -->
                            <div class="form-row">
                                <!-- Forgot password -->
                                <div class="col-12 col-lg-6">
                                    <a href="<?= Url::to(['/user/forgotpassword']) ?>">Forgot Password</a>
                                </div>
                                <!-- Submit button -->
                                <div class="col-12 col-lg-6 d-flex justify-content-end">
                                    <button type="submit" class="btn bg-gradient-primary rounded-pill px-4"><i class="fas fa-key pr-2"></i> LOGIN</button>
                                </div>
                            </div>
                            <?php ActiveForm::end() ?>
                        </div>
                    </div>
                    <div class="row mt-4 mb-2">
                        <div class="col-12">
                            <p class="mb-0 text-white">Dont have an account? <a class="btn btn-outline-primary rounded-pill btn-sm" href="<?= Url::to(['/user/register']) ?>">Register</a></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center text-white py-0">
                    <small>Developed by Jackson Konjengbam & Gobinda Deb</small>
                </div>
            </div>
        </div>
    </div>
</div>