<?php

/** @var yii\web\View $this */
/** @var app\models\User $user */

use yii\helpers\Url;

$this->title = 'Forgotpassword';
?>

<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-12 col-md-8 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-12 text-center">
                            <a class="text-decoration-none" href="<?= Url::to(['/site']) ?>">
                                <h2 class="app-font-michroma text-dark heading"><?= Yii::$app->name ?></h2>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="text-center mb-3">
                                <img src="/default/email.svg" class="img-fluid" width="160px" alt="Email Image">
                            </div>
                            <p>
                                We have sent a verification link to <?= $user->email ?> email account.
                                Please check your email.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>