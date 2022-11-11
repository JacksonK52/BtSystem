<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
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
                <div class="card-body rounded">
                    <div class="text-center mb-5">
                        <h2 class="app-font-poppins app-font-600 text-white heading"><?= Yii::$app->name ?></h2>
                    </div>

                    <div class="px-4">
                        <?php $form = ActiveForm::begin() ?>
                        <!-- Email -->
                        <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'inputmode' => 'email'])->label('Email', ['class' => 'text-white']) ?>
                        <!-- Password -->
                        <?= $form->field($model, 'password')->passwordInput(["placeholder" => "Password"])->label("Password", ["class" => "text-white"]) ?>
                        <!-- Button -->
                        <div class="form-group float-right">
                            <button type="submit" class="btn bg-gradient-primary rounded-pill px-4"><i class="fas fa-key pr-2"></i> LOGIN</button>
                        </div>
                        <?php ActiveForm::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Allow User to Enter Only Number
    const numberValidation = (e) => {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) { // backspace key
            if (unicode < 48 || unicode > 57) //if not a number
                return false; //disable key press
        }
    }
</script>