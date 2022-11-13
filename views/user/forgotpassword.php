<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

$this->registerCssFile("@web/css/auth/forgotpassword.css", ['depends' => \app\assets\AuthAsset::class]);
$this->registerJsFile("@web/js/auth/forgotpassword.js", ['depends' => \app\assets\AuthAsset::class]);

$this->title = 'Forgot Password';
?>

<h1>Forgot password</h1>

<?php $form = ActiveForm::begin() ?>
    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email', 'inputmode' => 'email'])->label('Email *') ?>
    <div class="form-group float-right">
        <button class="btn bg-gradient-primary">Find</button>
    </div>
<?php ActiveForm::end() ?>