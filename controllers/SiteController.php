<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Index action.
     * ===============================================================
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     * ===============================================================
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        // Selecting Auth Layout
        $this->layout = 'auth';

        // If user is already login
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            // Find User Data
            $user = User::find()->andWhere('email = :uEmail', ['uEmail' => $model->email])->one();
            if (empty($user)) {
                Yii::$app->session->setFlash('danger', 'Invalid Email and Password!');
                return $this->refresh();
            }

            // Check Account Status
            if ($user->status == User::STATUS_INACTIVE || $user->status == USer::STATUS_DELETED) {
                Yii::$app->session->setFlash('danger', 'Account Block By Super Admin!');
                return $this->refresh();
            }

            // Login
            if (!$model->login()) {
                Yii::$app->session->setFlash('danger', 'Invalid Email and Password!');
                return $this->refresh();
            }

            return $this->redirect(['/site']);
        }

        $model->password = '';
        $context = [
            'model' => $model,
        ];
        return $this->render('login', $context);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
