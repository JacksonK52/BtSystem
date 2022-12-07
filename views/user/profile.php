<?php

/** @var yii\web\View $this */
/** @var app\Model\User $user */
/** @var app\Model\Profile $profile */
/** @var app\Model\DynamicModel $model */

use app\models\User;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;

$param = Yii::$app->getRequest()->getQueryParam('slug');

$this->title = "Profile"
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profile </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= Url::to(['/user/index']) ?>">User Panel</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div><!-- /.content-header -->

<!-- Main content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-3">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-12">
                    <!-- Profile Card -->
                    <div class="card card-info card-outline">
                        <div class="card-body box-profile">
                            <!-- User Image -->
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle" src="<?= $user->img_location ?>" alt="User profile picture">
                            </div>
                            <!-- User name -->
                            <h3 class="profile-username text-center"><?= ucfirst($user->name) ?></h3>
                            <!-- Role -->
                            <p class="text-muted text-center"><?= $user->getRole($user->role) ?></p>
                            <!-- Control -->
                            <div class="d-flex justify-content-around p-2">
                                <!-- Edit User Information -->
                                <a href="<?= Url::to(['/user/update', 'slug' => $param]) ?>" class="btn btn-info btn-sm rounded-pill px-4"><i class="fas fa-pencil"></i> Update</a>
                                <!-- Remove Image -->
                                <?php if ($user->img_location == '/default/user.png') : ?>
                                    <a href="<?= Url::to(['/user/upload-image', 'slug' => $param]) ?>" class="btn btn-primary btn-sm rounded-pill px-4"><i class="fas fa-cloud-upload"></i> Profile Pic</a>
                                <?php else : ?>
                                    <button class="btn btn-danger btn-sm rounded-pill px-4" onclick="$('#user_img_slug').val('<?= $user->slug ?>')" data-toggle="modal" data-target="#RemoveConfirmation"><i class="fas fa-trash"></i> Profile Pic</button>
                                <?php endif; ?>
                            </div>
                            <!-- NOTE: Change -->
                            <ul class="list-group list-group-unbordered mb-3">
                                <!-- Email -->
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right text-dark"><?= $user->email ?></a>
                                </li>
                                <!-- Verify -->
                                <li class="list-group-item">
                                    <b>Email Status</b> <a class="float-right text-dark"><?= $user->getVerify($user->verify) ?></a>
                                </li>
                                <!-- Role -->
                                <li class="list-group-item">
                                    <b>Role</b> <a class="float-right text-dark"><?= $user->getRole($user->role) ?></a>
                                </li>
                                <!-- Status -->
                                <li class="list-group-item">
                                    <b>Status</b> <a class="float-right text-dark"><?= $user->getStatus($user->status) ?></a>
                                </li>
                            </ul>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div>
                <div class="col-12 col-md-6 col-lg-12">
                    <!-- Additional Information Box -->
                    <div class="card card-info">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Additional Information</h3>
                                <?php if (empty($profile)) : ?>
                                    <a href="<?= Url::to(['/profile/add', 'slug' => $param]) ?>" title='Add User Profile'><i class="fas fa-plus"></i></a>
                                <?php else : ?>
                                    <a href="<?= Url::to(['/profile/update', 'slug' => $param]) ?>" title='Update User Profile'><i class="fas fa-pencil"></i></a>
                                <?php endif; ?>
                            </div>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <?php if (empty($profile)) : ?>
                                <strong>Data Not Found</strong>
                            <?php else : ?>
                                <!-- Employee Id -->
                                <strong><i class="fas fa-id-badge mr-1"></i> Employee Id</strong>
                                <p class="text-muted"><?= (empty($profile->emp_id) ? 'Data Not Found' : $profile->emp_id) ?></p>
                                <hr>
                                <!-- Mobile -->
                                <strong><i class="fas fa-mobile-android-alt mr-1"></i> Mobile</strong>
                                <p class="text-muted mb-1"><?= (empty($profile->mobile) ? 'Data Not Found' : $profile->mobile) ?></p>
                                <hr>
                                <!-- Adddress One -->
                                <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                                <p class="text-muted mb-1"><?= (empty($profile->address_line_one) ? 'Data Not Found' : $profile->address_line_one) ?></p>
                                <p class="text-muted mb-1"><?= (empty($profile->address_line_two) ? '' : $profile->address_line_two) ?></p>
                                <p class="text-muted mb-1">Landmark: <?= (empty($profile->landmark) ? '' : $profile->landmark) ?></p>
                                <p class="text-muted mb-1">District: <?= (empty($profile->district) ? '' : $profile->district) ?></p>
                                <p class="text-muted mb-1">Pincode: <?= (empty($profile->pincode) ? '' : $profile->pincode) ?></p>
                                <p class="text-muted mb-1">State: <?= (empty($profile->state) ? '' : $profile->state) ?></p>
                                <hr>
                                <div class="d-flex">
                                    <?php if (empty($profile->updatedBy->name)) : ?>
                                        <small>Created By: <?= empty($profile->createdBy->name) ? 'N/A' : $profile->createdBy->name ?></small>
                                    <?php else : ?>
                                        <small>Last Updated By: <?= $profile->updatedBy->name ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div>
            </div>
        </div><!-- /.col -->

        <div class="col-12 col-lg-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <!-- <li class="nav-item"><a class="nav-link active" href="#feedback" data-toggle="tab">Feedback</a></li> -->
                        <!-- <li class="nav-item"><a class="nav-link" href="#wishlist" data-toggle="tab">Wishlist</a></li> -->
                        <li class="nav-item"><a class="nav-link active" href="#changePassword" data-toggle="tab">Change Password</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Feedback -->
                        <!-- <div class="active tab-pane" id="feedback">
                            <h1>Feedback</h1>
                        </div> -->

                        <!-- Wishlist -->
                        <!-- <div class="tab-pane" id="wishlist">

                        </div> -->

                        <!-- Change Password -->
                        <div class="active tab-pane" id="changePassword">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h1>Change Password</h1>
                                    <small class="text-danger">* Password should be minimum of 6 character length</small>
                                </div>
                                <div class="card-body">
                                    <?php $form = ActiveForm::begin() ?>
                                    <div class="form-row justify-content-center">
                                        <!-- Password -->
                                        <div class="col-10">
                                            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Enter Password'])->label('Password *') ?>
                                        </div>
                                        <!-- Confirm Password -->
                                        <div class="col-10">
                                            <?= $form->field($model, "confirm_password")->passwordInput(["placeholder" => 'Confirm Password'])->label('Confirm Password *') ?>
                                        </div>
                                        <!-- Buttons -->
                                        <div class="col-10">
                                            <div class="float-right mt-3">
                                                <button class="btn btn-info rounded-pill px-4">Update Password</button>
                                            </div>
                                        </div>
                                    </div>

                                    <?php ActiveForm::end() ?>
                                </div>
                            </div>
                        </div><!-- /.tab-pane -->
                    </div><!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->

<!-- Remove Modal -->
<div class="modal fade" id="RemoveConfirmation" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="RemoveConfirmationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title" id="RemoveConfirmationLabel">Remove Profile Pic</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                <h5>Are you sure you want to remove profile pic?</h5>
                <form action="<?= Url::to(['/user/remove-image']) ?>" method="post">
                    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                    <input type="hidden" name="user_img_slug" id="user_img_slug">
                    <div class="d-flex justify-content-center mt-4">
                        <button class="btn bg-gradient-danger rounded-pill px-5 mr-2">Yes</button>
                        <button type="button" class="btn bg-gradient-secondary rounded-pill px-5" data-dismiss="modal">No</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>