<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Favorite;
use app\models\FavoriteList;
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
                'only' => ['index', 'features', 'add-favorite', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'features', 'add-favorite', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            // Status Validation
                            if (Yii::$app->user->identity->status === User::STATUS_INACTIVE || Yii::$app->user->identity->status === User::STATUS_DELETED) {
                                return $this->redirect(['/user/force-logout']);
                            }

                            // Role Validation - Everyone is allowed.

                            return true;
                        }
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
        // User Information
        $countAdmin = 0;
        $countTeamLeader = 0;
        $countDeveloper = 0;
        $countTester = 0;

        // Project Information

        // Find and Validate Favorite List Data
        $favLists = FavoriteList::find()->andWhere('user_id = :uId and status = :active', ['uId' => Yii::$app->user->getId(), 'active' => FavoriteList::STATUS_ACTIVE])->all();
        if (!empty($favLists)) {
            foreach ($favLists as $favList) {
                // User dashboard
                if ($favList->favorite->title == 'User') {
                    // Find, Validate and Count User Data
                    $users = User::find()->andWhere('status = :active', ['active' => User::STATUS_ACTIVE])->all();
                    if (!empty($users)) {
                        foreach ($users as $user) {
                            // count total admin
                            $countAdmin += ($user->role === User::ROLE_ADMIN ? 1 : 0);
                            $countTeamLeader += ($user->role === User::ROLE_TEAM_LEADER ? 1 : 0);
                            $countDeveloper += ($user->role === User::ROLE_DEVELOPER ? 1 : 0);
                            $countTester += ($user->role === User::ROLE_TESTER ? 1 : 0);
                        }
                    }
                }

                // Project dashboard
                if($favList->favorite->title == 'Project') {
                    
                }
            }
        }

        $context = [
            'favLists' => $favLists,
            'countAdmin' => $countAdmin,
            'countTeamLeader' => $countTeamLeader,
            'countDeveloper' => $countDeveloper,
            'countTester' => $countTester
        ];
        return $this->render('index', $context);
    }

    /**
     * Features
     * ===============================================================
     */
    public function actionFeatures()
    {
        if (Yii::$app->user->identity->role === User::ROLE_SUPERADMIN) {
            // If Current User is Super-admin
            $favorites = Favorite::find()->andWhere('status = :active', ['active' => Favorite::STATUS_ACTIVE])->all();
        } elseif (Yii::$app->user->identity->role === User::ROLE_ADMIN) {
            // If Current User is Admin
            $favorites = Favorite::find()->andWhere('access_level != :superAdmin and status = :active', ['superAdmin' => Favorite::ACCESS_LEVEL_SUPERADMIN, 'active' => Favorite::STATUS_ACTIVE])->all();
        } elseif (Yii::$app->user->identity->role === User::ROLE_TEAM_LEADER) {
            // If Current user is team leader
            $favorites = Favorite::find()->andWhere('access_level != :superAdmin and access_level != :admin and status = :active', ['superAdmin' => Favorite::ACCESS_LEVEL_SUPERADMIN, 'admin' => Favorite::ACCESS_LEVEL_ADMIN, 'active' => Favorite::STATUS_ACTIVE])->all();
        } elseif (Yii::$app->user->identity->role === User::ROLE_TESTER || Yii::$app->user->identity->role === User::ROLE_DEVELOPER) {
            // If Current user is tester or developer
            $favorites = Favorite::find()->andWhere('access_level = :level and status = :active', ['level' => Favorite::ACCESS_LEVEL_EVERYONE, 'active' => Favorite::STATUS_ACTIVE])->all();
        }

        $context = [
            'favorites' => $favorites,
        ];
        return $this->render('features', $context);
    }

    /**
     * Add Favorite
     * ===============================================================
     */
    public function actionAddFavorite()
    {
        // Check if controller is call from ajax request
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $datas = Yii::$app->request->post();

            // Check Param
            if (empty($datas['id'])) {
                return [
                    'status' => 'danger',
                    'msg' => 'Missing Required Information!'
                ];
            }

            // Find and Validate Favorite List Data
            $favList = FavoriteList::find()->andWhere('user_id = :uId and favorite_id = :favId', ['uId' => Yii::$app->user->getId(), 'favId' => $datas['id']])->one();
            if (empty($favList)) {
                // Current User does not exist in favorite list
                $favList = new FavoriteList([
                    'slug' => Yii::$app->BtsystemComponent->slugGenerator(),
                    'user_id' => Yii::$app->user->getId(),
                    'favorite_id' => $datas['id'],
                    'status' => FavoriteList::STATUS_ACTIVE,
                ]);
                if (!$favList->save()) {
                    return [
                        'status' => 'danger',
                        'msg' => 'Fail To Add Favorite!'
                    ];
                }

                return [
                    'status' => 'success',
                    'msg' => 'Favorite Added!'
                ];
            }

            // Favorite List Information
            $favList->status = ($favList->status === FavoriteList::STATUS_ACTIVE ? FavoriteList::STATUS_INACTIVE : FavoriteList::STATUS_ACTIVE);
            if (!$favList->update(true)) {
                return [
                    'status' => 'danger',
                    'msg' => 'Fail To Add Favorite!'
                ];
            }

            // Return Status and Msg according to status
            if ($favList->status === FavoriteList::STATUS_ACTIVE) {
                return [
                    'status' => 'success',
                    'msg' => 'Favorite Added!'
                ];
            } else {
                return [
                    'status' => 'success',
                    'msg' => 'Favorite Remove!'
                ];
            }
        }
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
