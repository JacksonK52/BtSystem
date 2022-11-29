<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\User;
use yii\base\DynamicModel;
use app\models\Profile;
use yii\web\UploadedFile;

class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'profile', 'add', 'update', 'change-status', 'upload-image', 'delete', 'remove-image'],
                'rules' => [
                    [
                        'actions' => ['index', 'profile', 'add', 'update', 'change-status', 'upload-image', 'delete', 'remove-image'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'remove-image' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Index action
     * ===========================================
     */
    public function actionIndex()
    {
        // Find User Data
        if (Yii::$app->user->identity->role == User::ROLE_SUPERADMIN) {
            $users = User::find()->andWhere('role != :superAdmin and status != :deleted', ['superAdmin' => User::ROLE_SUPERADMIN, 'deleted' => User::STATUS_DELETED])->all();
        } else {
            $users = User::find()->andWhere('role != :superAdmin and role != :admin and status != :deleted', ['superAdmin' => User::ROLE_SUPERADMIN, 'admin' => User::ROLE_ADMIN, 'deleted' => User::STATUS_DELETED])->all();
        }

        $context = [
            'users' => $users,
        ];
        return $this->render('index', $context);
    }

    /**
     * Profile
     * ===========================================
     * @param $slug app\models\User
     */
    public function actionProfile($slug = null)
    {
        // Find Necessary User Data
        if (empty($slug) || $slug == null) {
            // If slug is empty then load current user profile
            $user = User::find()->andWhere(['id' => Yii::$app->user->getId()])->one();
        } else {
            // If slug exist then load particular user
            $user = User::find()->andWhere('slug = :uSlug and status != :deleted', ['uSlug' => $slug, 'deleted' => User::STATUS_DELETED])->one();
            if (empty($user)) {
                Yii::$app->session->setFlash('danger', 'Missing Required Information!');
                return $this->refresh();
            }
        }

        // Find Profile Data
        $profile = Profile::find()->andWhere('user_id = :uId and status = :active', ['uId' => $user->id, 'active' => Profile::STATUS_ACTIVE])->one();

        // Change Password Model
        $model = new DynamicModel(["password", "confirm_password"]);
        $model->addRule(["password", "confirm_password"], "required")
            ->addRule(["password", "confirm_password"], "string", ["min" => 6])
            ->addRule('confirm_password', 'compare', ['compareAttribute' => 'password', "message" => "Password doesn't match"]);

        $context = [
            'user' => $user,
            'profile' => $profile,
            'model' => $model,
        ];
        return $this->render('profile', $context);
    }

    /**
     * Forgotpassword action
     * ===========================================
     */
    public function actionForgotpassword()
    {
        // Selecting Auth Layout
        $this->layout = 'auth';

        // If user is already login
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $model = new User();
        if ($model->load(Yii::$app->request->post())) {
            // Find User
            $user = User::find()->andWhere('email = :uEmail and role != :superAdmin and status = :active', ['uEmail' => $model->email, 'superAdmin' => User::ROLE_SUPERADMIN, 'active' => User::STATUS_ACTIVE])->one();
            if (empty($user)) {
                Yii::$app->session->setFlash('danger', 'User Data Not Found!');
                return $this->refresh();
            }

            // Send Email
            \Yii::$app->mailer->htmlLayout = "@app/mail/layouts/html";
            $email = Yii::$app->mailer->compose(['html' => '@app/mail/views/forgotpassword'], ['name' => $user->name, 'token' => $user->token_id, 'authkey' => $user->auth_key]);
            $email->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']]);
            $email->setTo($user->email);
            $email->setSubject('Password reset for your btsystem account');
            if (!$email->send()) {
                Yii::$app->session->setFlash('danger', 'Fail To Change Password!');
                return $this->refresh();
            }

            return $this->redirect(['forgotpassword-notification', 'slug' => $user->slug]);
        }

        $context = [
            'model' => $model,
        ];
        return $this->render('forgotpassword', $context);
    }

    /**
     * Forgotpassword Notification
     * ===========================================
     */
    public function actionForgotpasswordNotification($slug = null)
    {
        // Selecting Auth Layout
        $this->layout = 'auth';

        // Check Parameter
        if (empty($slug) || $slug == null) {
            Yii::$app->session->setFlash('danger', 'Missing Required Information!');
            return $this->redirect(['/site/login']);
        }

        // Find and Validate User
        $user = User::find()->andWhere('slug = :uSlug and status = :active', ['uSlug' => $slug, 'active' => User::STATUS_ACTIVE])->one();
        if (empty($user)) {
            Yii::$app->session->setFlash('danger', 'Data Not Found!');
            return $this->redirect(['forgotpassword']);
        }

        // If user is already login
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $context = [
            'user' => $user,
        ];
        return $this->render('forgotpassword-notification', $context);
    }

    /**
     * Resetpass action
     * ===========================================
     */
    public function actionResetpass($token, $auth)
    {
        // Selecting Auth Layout
        $this->layout = 'auth';

        // Check Param
        if (empty($token) || empty($auth)) {
            Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
            return $this->redirect(['/user/forgotpassword']);
        }

        // Find and Validate User Data
        $user = User::find()->andWhere('token_id = :token and auth_key = :auth and role != :superAdmin and status = :active', ['token' => $token, 'auth' => $auth, 'superAdmin' => User::ROLE_SUPERADMIN, 'active' => User::STATUS_ACTIVE])->one();
        if (empty($user)) {
            Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
            return $this->redirect(['/user/forgotpassword']);
        }

        // Super-admin password can not be change
        if ($user->role == User::ROLE_SUPERADMIN) {
            // Change Super-admin auth-key
            $auth = Yii::$app->security->generateRandomString();
            User::updateAll(['auth_key' => $auth], ['id' => $user->id]);

            Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
            return $this->redirect(['/site']);
        }

        $model = new User();
        if ($model->load(Yii::$app->request->post())) {
            // Update User Password
            $salt = rand(100000, 999999);
            $pass = password_hash($model->password . $salt, PASSWORD_DEFAULT);
            $auth = Yii::$app->security->generateRandomString();
            if (User::updateAll(['salt' => $salt, 'password' => $pass, 'auth_key' => $auth], ['id' => $user->id])) {
                Yii::$app->session->setFlash('success', 'Password Changed!');
                return $this->redirect(['/site']);
            } else {
                Yii::$app->session->setFlash('danger', 'Fail To Change Password!');
                return $this->refresh();
            }
        }

        $context = [
            'model' => $model,
        ];
        return $this->render('resetpass', $context);
    }

    /**
     * Register
     * ===========================================
     */
    public function actionRegister()
    {
        // Selecting Auth Layout
        $this->layout = 'auth';

        // User Model
        $model = new User();
        if ($model->load(Yii::$app->request->post())) {
            // Check if Email already exist
            if (!$model->validateEmail($model->email)) {
                Yii::$app->session->setFlash('danger', 'Email Already Exist!');
                $model->email = '';
                return $this->redirect(['register', ['model' => $model]]);
            }

            // User Information
            $model->slug = Yii::$app->BtsystemComponent->slugGenerator($model->name);
            $model->salt = rand(100000, 999999);

            // Upload Image
            if ($model->img_location = UploadedFile::getInstance($model, "img_location")) {
                // Create directory
                if (!is_dir('upload/user/')) {
                    mkdir('upload/user/', 0777);
                }

                // Uploaded Image Information
                $name = strtolower(explode(" ", $model->name)[0]);
                $ext = $model->img_location->extension;
                // Save Image
                $img_name = $name . "-" . Yii::$app->security->generateRandomString(8);
                $img_location = 'upload/user/' . $img_name . '.' . $ext;
                $model->img_location->saveAs($img_location);
                $model->img_location = '/' . $img_location;
            } else {
                $model->img_location = '/default/user.png';
            }

            if ($model->save()) {
                // Send Email
                \Yii::$app->mailer->htmlLayout = "@app/mail/layouts/html";
                $email = Yii::$app->mailer->compose(['html' => '@app/mail/views/email-verification'], ['name' => $model->name, 'token' => $model->token_id, 'authkey' => $model->auth_key]);
                $email->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']]);
                $email->setTo($model->email);
                $email->setSubject('Password reset for your btsystem account');
                $email->send();

                return $this->redirect(['/user/account-registered', 'slug' => $model->slug]);
            } else {
                Yii::$app->session->setFlash('danger', 'Fail To Register!');
                return $this->refresh();
            }
        }

        $context = [
            'model' => $model,
        ];
        return $this->render('register', $context);
    }

    /**
     * Account Registered
     * ===========================================
     */
    public function actionAccountRegistered($slug = null)
    {
        // Selecting Auth Layout
        $this->layout = 'auth';

        // Check Parameter
        if (empty($slug) || $slug == null) {
            Yii::$app->session->setFlash('danger', 'Missing Required Information!');
            return $this->redirect(['/site/login']);
        }

        // Find and Validate User Data
        $user = User::find()->andWhere('slug = :uSlug and status = :active', ['uSlug' => $slug, 'active' => User::STATUS_ACTIVE])->one();
        if (empty($user)) {
            Yii::$app->session->setFlash('danger', 'Data Not Found!');
            return $this->redirect(['/site/login']);
        }

        // Check user email verification
        if ($user->verify == User::VERIFY_YES) {
            Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
            return $this->redirect(['/site/login']);
        }

        return $this->render('account-registered');
    }

    /**
     * Verify Email
     * ===========================================
     */
    public function actionVerifyEmail($token = null, $auth = null)
    {
        // Selecting Auth Layout
        $this->layout = 'auth';

        // Check Parameter
        if ((empty($token) || $token == null) || (empty($auth) || $auth == null)) {
            Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
            return $this->redirect(['/site/login']);
        }

        // Find and Validate User
        $user = User::find()->andWhere('token_id = :uToken and auth_key = :uAuth and role != :superAdmin', ['uToken' => $token, 'uAuth' => $auth, 'superAdmin' => User::ROLE_SUPERADMIN])->one();
        if (empty($user)) {
            Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
            return $this->redirect(['/user/forgotpassword']);
        }

        // Update User Information
        $auth = Yii::$app->security->generateRandomString();
        if (!User::updateAll(['auth_key' => $auth, 'verify' => User::VERIFY_YES], ['id' => $user->id])) {
            Yii::$app->session->setFlash('danger', 'Fail To Verify Email!');
            return $this->redirect(['/site/login']);
        }

        Yii::$app->user->login(User::findIdentity($user->id), 3600 * 24 * 30);
        return $this->redirect(['/site']);
    }

    /**
     * Add
     * ===========================================
     */
    public function actionAdd()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post())) {
            // Check If email already exist
            $temp = User::find()->andWhere('email = :uEmail and status != :deleted', ['uEmail' => $model->email, 'deleted' => User::STATUS_DELETED])->one();
            if (!empty($temp)) {
                Yii::$app->session->setFlash('danger', 'Email Already Taken!');
                return $this->refresh();
            }

            // User Information
            $model->slug = Yii::$app->BtsystemComponent->slugGenerator($model->name);
            $model->salt = rand(100000, 999999);
            // Upload Image
            if ($model->img_location = UploadedFile::getInstance($model, "img_location")) {
                // Create directory
                if (!is_dir('upload/user/')) {
                    mkdir('upload/user/', 0777);
                }

                // Uploaded Image Information
                $name = strtolower(explode(" ", $model->name)[0]);
                $ext = $model->img_location->extension;
                // Save Image
                $img_name = $name . "-" . Yii::$app->security->generateRandomString(8);
                $img_location = 'upload/user/' . $img_name . '.' . $ext;
                $model->img_location->saveAs($img_location);
                $model->img_location = '/' . $img_location;
            } else {
                $model->img_location = '/default/user.png';
            }
            $model->verify = User::VERIFY_YES;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'User Registered!');
            } else {
                Yii::$app->session->setFlash('danger', 'Fail To Registered!');
            }

            return $this->refresh();
        }

        $context = [
            'model' => $model,
        ];
        return $this->render('add', $context);
    }

    /**
     * Update
     * ===========================================
     * 
     * @param $slug app\models\User
     */
    public function actionUpdate($slug = null)
    {
        // Find User Data
        if (empty($slug) || $slug === null) {
            // If slug is empty then user want to edit there own account
            $user = User::find()->andWhere('id = :uId and status != :deleted', ['uId' => Yii::$app->user->getId(), 'deleted' => User::STATUS_DELETED])->one();
        } else {
            // If slug is not empty then user want to edit somewant else account
            if (Yii::$app->user->identity->role === User::ROLE_DEVELOPER || Yii::$app->user->identity->role === User::ROLE_TESTER) {
                // If user is Developer or Tester then user has no authorized
                Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
                return $this->redirect('index');
            }

            $user = User::find()->andWhere('slug = :uSlug and role != :superAdmin and status != :deleted', ['uSlug' => $slug, 'superAdmin' => User::ROLE_SUPERADMIN, 'deleted' => User::STATUS_DELETED])->one();
        }

        // Validate User Data
        if (empty($user)) {
            Yii::$app->session->setFlash('danger', 'Data Not Found!');
            return $this->redirect(['index']);
        }

        // After Form Submit
        if ($user->load(Yii::$app->request->post())) {
            if (!User::updateAll(['name' => $user->name], ['id' => $user->id])) {
                Yii::$app->session->setFlash('danger', 'Fail To Update!');
                return $this->refresh();
            }

            Yii::$app->session->setFlash('success', 'User Name Updated!');
            return $this->redirect(['profile', 'slug' => Yii::$app->getRequest()->getQueryParam('slug')]);
        }

        $context = [
            'user' => $user,
        ];
        return $this->render('update', $context);
    }

    /** Change Status 
     * ===========================================
     * Super-admin account can not be disabled
     * 
     * @param $slug app\models\User
     */
    public function actionChangeStatus($slug = null)
    {
        // Check Param
        if (empty($slug) || $slug == null) {
            Yii::$app->session->setFlash('danger', 'Missing Required Information!');
            return $this->refresh();
        }

        // Find and Validate User Data
        $user = User::find()->andWhere('slug = :uSlug and role != :superAdmin and status != :deleted', ['uSlug' => $slug, 'superAdmin' => User::ROLE_SUPERADMIN, 'deleted' => User::STATUS_DELETED])->one();
        if (empty($user)) {
            Yii::$app->session->setFlash('danger', 'Data Not Found!');
            return $this->redirect(['index']);
        }

        // User Information
        $status = ($user->status === User::STATUS_ACTIVE ? User::STATUS_INACTIVE : User::STATUS_ACTIVE);
        if (User::updateAll(['status' => $status], ['id' => $user->id])) {
            Yii::$app->session->setFlash('success', 'Status Changed!');
        } else {
            Yii::$app->session->setFlash('danger', 'Fail To Change Status!');
        }

        return $this->redirect(['index']);
    }

    /**
     * Delete
     * ===========================================
     */
    public function actionDelete()
    {
        // Get Post Data and Validate
        if (Yii::$app->request->isPost) {
            $datas = Yii::$app->request->post();

            // Validate Param
            if (empty($datas['user_slug'])) {
                Yii::$app->session->setFlash('danger', 'Missing Required Information!');
                return $this->redirect(['index']);
            }

            // Find and Validate User Data
            $user = User::find()->andWhere('slug = :uSlug and role != :superAdmin and status != :deleted', ['uSlug' => $datas['user_slug'], 'superAdmin' => User::ROLE_SUPERADMIN, 'deleted' => User::STATUS_DELETED])->one();
            if (empty($user)) {
                Yii::$app->session->setFlash('danger', 'Data Not Found!');
                return $this->redirect(['index']);
            }

            if (User::updateAll(['status' => User::STATUS_DELETED], ['id' => $user->id])) {
                Yii::$app->session->setFlash('success', 'User Account Deleted!');
            } else {
                Yii::$app->session->setFlash('danger', 'Fail To Delete User!');
            }

            return $this->redirect(['index']);
        }
    }

    /** Remove Image
     * ===========================================
     */
    public function actionRemoveImage()
    {        
        // Get Post Data and Validate
        if (Yii::$app->request->isPost) {
            $datas = Yii::$app->request->post();

            // Validate Param
            if (empty($datas['user_img_slug'])) {
                Yii::$app->session->setFlash('danger', 'Missing Required Information!');
                return $this->redirect(['index']);
            }

            // Find and Validate User Data
            if (Yii::$app->user->identity->role === User::ROLE_SUPERADMIN) {
                // If user is Super-admin
                $user = User::find()->andWhere('slug = :uSlug and status != :deleted', ['uSlug' => $datas['user_img_slug'], 'deleted' => User::STATUS_DELETED])->one();
                if (empty($user)) {
                    Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
                    return $this->redirect(['profile', 'slug' => $datas['user_img_slug']]);
                }
            } else {
                // If user is not Super-admin
                $user = User::find()->andWhere('slug = :uSlug and role != :superAdmin and status != :deleted', ['uSlug' => $datas['user_img_slug'], 'superAdmin' => User::ROLE_SUPERADMIN, 'deleted' => User::STATUS_DELETED])->one();
                if (empty($user)) {
                    Yii::$app->session->setFlash('danger', 'Data Not Found!');
                    return $this->redirect(['profile', 'slug' => $datas['user_img_slug']]);
                }
            }

            // If Profile Pic is default Image
            if($user->img_location == '/default/user.png') {
                Yii::$app->session->setFlash('info', 'Unable To Remove Default Image!');
                return $this->redirect(['profile', 'slug' => $datas['user_img_slug']]);
            }

            // User Information
            $temp_img_location = $user->img_location;
            if (!unlink(substr($temp_img_location, 1))) {
                Yii::$app->session->setFlash('danger', 'Fail To Remover Profile Pic!');
                return $this->redirect(['profile', 'slug' => $datas['user_img_slug']]);
            }

            User::updateAll(['img_location' => '/default/user.png'], ['id' => $user->id]);
            Yii::$app->session->setFlash('success', 'Profile Picture Removed!');
            return $this->redirect(['profile', 'slug' => $datas['user_img_slug']]);
        }
    }

    /**
     * Upload Image
     * ===========================================
     * 
     * @param $slug app\models\User
     */
    public function actionUploadImage($slug = null)
    {
        // Find and Validate User Data
        if(empty($slug) || $slug == null) {
            // Find Current User Data
            $user = User::find()->andWhere('id = :uId and status != :deleted', ['uId' => Yii::$app->user->getId(), 'deleted' => User::STATUS_DELETED])->one();
            if(empty($user)) {
                Yii::$app->session->setFlash('danger', 'Data Not Found!');
                return $this->redirect(['profile']);
            } elseif($user->img_location != '/default/user.png') {
                Yii::$app->session->setFlash('info', 'Profile Pic Already Exist!');
                return $this->redirect(['profile']);
            }
        } else {
            // Search For A Particular User
            $user = User::find()->andWhere('slug = :uSlug and role != :superAdmin and status != :deleted', ['uSlug' => $slug, 'superAdmin' => User::ROLE_SUPERADMIN, 'deleted' => User::STATUS_DELETED])->one();
            if(empty($user)) {
                Yii::$app->session->setFlash('danger', 'Data Not Found!');
                return $this->redirect(['profile', 'slug' => $slug]);
            } elseif($user->img_location != '/default/user.png') {
                Yii::$app->session->setFlash('info', 'Profile Pic Already Exist!');
                return $this->redirect(['profile', 'slug' => $slug]);
            }
        }

        // After Form submit
        if($user->load(Yii::$app->request->post())) {
            // Upload Profile Pic
            if ($user->img_location = UploadedFile::getInstance($user, "img_location")) {
                // Create directory
                if (!is_dir('upload/user/')) {
                    mkdir('upload/user/', 0777);
                }

                // Uploaded Image Information
                $name = strtolower(explode(" ", $user->name)[0]);
                $ext = $user->img_location->extension;
                // Save Image
                $img_name = $name . "-" . Yii::$app->security->generateRandomString(8);
                $img_location = 'upload/user/' . $img_name . '.' . $ext;
                $user->img_location->saveAs($img_location);
                $user->img_location = '/' . $img_location;
            } else {
                $user->img_location = '/default/user.png';
            }
            
            if(User::updateAll(['img_location' => $user->img_location], ['id' => $user->id])) {
                Yii::$app->session->setFlash('success', 'Profile Pic Uploaded!');
            } else {
                Yii::$app->session->setFlash('danger', 'Fail To Upload Profile Pic!');
            }

            return $this->redirect(['profile', 'slug' => $slug]);
        }

        $context = [
            'user' => $user,
        ];
        return $this->render('upload-image', $context);
    }
}
