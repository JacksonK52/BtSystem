<?php

/** @var yii\web\View $this */
/** @var app\models\RegisterForm $model */

use yii\helpers\Url;

$this->title = 'Register Account';
?>

<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-12 col-md-10 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3>Register Account</h3>
                </div>
                <div class="card-body">
                    <!-- Register Form -->
                    <div class="row justify-content-center align-items-center">
                        <div class="col-12">
                            <?= $this->render('_form', ['model' => $model]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>