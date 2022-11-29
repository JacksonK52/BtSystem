<?php

/** @var yii\web\View $this */
/** @var app\models\Profile $model */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin() ?>
<!-- Employee Id & Mobile -->
<div class="form-row">
    <!-- Employee Id -->
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'emp_id')->textInput(['placeholder' => 'Employee Id'])->label('Employee Id') ?>
    </div>
    <!-- Mobile -->
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'mobile')->textInput(['placeholder' => 'Mobile', 'inputmode' => 'numeric', 'min' => 10, 'max' => 10, "onkeypress" => "return numberValidation(event)"])->label('Mobile') ?>
    </div>
</div>
<!-- Address Line One & Two -->
<div class="form-row">
    <!-- Address Line One -->
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'address_line_one')->textarea(['placeholder' => 'Address Line One', 'rows' => 4])->label('Address Line One') ?>
    </div>
    <!-- Address Line Two -->
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'address_line_two')->textarea(['placeholder' => 'Address Line Two', 'rows' => 4])->label('Address Line Two') ?>
    </div>
</div>
<!-- Landmark & District -->
<div class="form-row">
    <!-- Landmark -->
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'landmark')->textInput(['placeholder' => 'Landmark'])->label('Landmark') ?>
    </div>
    <!-- District -->
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'district')->textInput(['placeholder' => 'District'])->label('District') ?>
    </div>
</div>
<!-- Pincode & State -->
<div class="form-row">
    <!-- Pincode -->
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'pincode')->textInput(['placeholder' => 'Pincode', "onkeypress" => "return numberValidation(event)"])->label('Pincode') ?>
    </div>
    <!-- State -->
    <div class="col-12 col-md-6">
        <?= $form->field($model, 'state')->textInput(['placeholder' => 'State'])->label('State') ?>
    </div>
</div>

<!-- Buttons -->
<div class="form-group float-right">
    <button class="btn bg-gradient-primary rounded-pill"><?= ($mode == 'update' ? 'Update' : 'Submit') ?></button>
</div>
<?php ActiveForm::end() ?>

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