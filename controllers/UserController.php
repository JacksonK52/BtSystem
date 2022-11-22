<?php

namespace app\controllers;

use app\models\RegisterForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\User;
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
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    '' => ['post'],
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
        if(Yii::$app->user->identity->role == User::ROLE_SUPERADMIN) {
            $users = User::find()->andWhere('role != :superAdmin and status != :deleted', ['superAdmin' => User::ROLE_SUPERADMIN, 'deleted' => User::STATUS_DELETED])->all();
        } else {

        }

        $context = [
            'users' => $users,
        ];
        return $this->render('index', $context);
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
            if(!$email->send()) {
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
        if(empty($slug) || $slug == null) {
            Yii::$app->session->setFlash('danger', 'Missing Required Information!');
            return $this->redirect(['/site/login']);
        }

        // Find and Validate User
        $user = User::find()->andWhere('slug = :uSlug and status = :active', ['uSlug' => $slug, 'active' => User::STATUS_ACTIVE])->one();
        if(empty($user)) {
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
        if($user->role == User::ROLE_SUPERADMIN) {
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
        if(empty($slug) || $slug == null) {
            Yii::$app->session->setFlash('danger', 'Missing Required Information!');
            return $this->redirect(['/site/login']);
        }

        // Find and Validate User Data
        $user = User::find()->andWhere('slug = :uSlug and status = :active', ['uSlug' => $slug, 'active' => User::STATUS_ACTIVE])->one();
        if(empty($user)) {
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
    public function actionVerifyEmail($token = null, $auth= null)
    {
        // Selecting Auth Layout
        $this->layout = 'auth';

        // Check Parameter
        if((empty($token) || $token == null) || (empty($auth) || $auth == null)) {
            Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
            return $this->redirect(['/site/login']);
        }

        // Find and Validate User
        $user = User::find()->andWhere('token_id = :uToken and auth_key = :uAuth and role != :superAdmin', ['uToken' => $token, 'uAuth' => $auth, 'superAdmin' => User::ROLE_SUPERADMIN])->one();
        if(empty($user)) {
            Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
            return $this->redirect(['/user/forgotpassword']);
        }

        // Update User Information
        $auth = Yii::$app->security->generateRandomString();
        if(!User::updateAll(['auth_key' => $auth, 'verify' => User::VERIFY_YES], ['id' => $user->id])) {
            Yii::$app->session->setFlash('danger', 'Fail To Verify Email!');
            return $this->redirect(['/site/login']);
        }
        
        Yii::$app->user->login(User::findIdentity($user->id), 3600*24*30);
        return $this->redirect(['/site']);
    }
}
