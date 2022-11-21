<?php

/** @var yii\web\View $this */
/** @var app\models\RegisterForm $model */

use yii\helpers\Url;

$this->title = 'Account Registered';
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
                            <h2>Email Verification Send</h2>
                            <p class="mt-4">
                                Congratulation you have successfuly registered your BtSystem account.
                                We have sent a verfication email in your registered email address, verify your email to start enjoying the service.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>