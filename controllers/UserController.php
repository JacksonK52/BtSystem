<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\User;

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
            $email->send();
        }

        $context = [
            'model' => $model,
        ];
        return $this->render('forgotpassword', $context);
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
        if(empty($token) || empty($auth)) {
            Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
            return $this->redirect(['/user/forgotpassword']);
        }

        // Find and Validate User Data
        $user = User::find()->andWhere('token_id = :token and auth_key = :auth and role != :superAdmin and status = :active', ['token' => $token, 'auth' => $auth, 'superAdmin' => User::ROLE_SUPERADMIN, 'active' => User::STATUS_ACTIVE])->one();
        if(empty($user)) {
            Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
            return $this->redirect(['/user/forgotpassword']);
        }

        $model = new User();
        if($model->load(Yii::$app->request->post())) {

            // Update User Password
            $salt = rand(100000, 999999);
            $pass = password_hash($model->password.$salt, PASSWORD_DEFAULT);
            $auth = Yii::$app->security->generateRandomString();
            if(User::updateAll(['salt' => $salt, 'password' => $pass, 'auth_key' => $auth], ['id' => $user->id])) {
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
}
