<?php

namespace app\controllers;

use app\models\Profile;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\User;

class ProfileController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'add'],
                'rules' => [
                    [
                        'actions' => ['index', 'add'],
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
     * Index
     * ======================================
     */
    public function actionIndex()
    {

        return $this->render('index');
    }

    /**
     * Add
     * ======================================
     * 
     * @param $slug app\models\Profile
     */
    public function actionAdd($slug = null)
    {
        // Find and Validate User Data
        if (empty($slug) || $slug == null) {
            // If slug is empty
            $user = User::find()->andWhere('id = :uId and status = :active', ['uId' => Yii::$app->user->getId(), 'active' => User::STATUS_ACTIVE])->one();
            if (empty($user)) {
                Yii::$app->session->setFlash('danger', 'Data Not Found!');
                return $this->redirect(['/user/profile']);
            }
        } else {
            // If Slug is not empty
            $user = User::find()->andWhere('slug = :uSlug and role != :superAdmin and status != :deleted', ['uSlug' => $slug, 'superAdmin' => User::ROLE_SUPERADMIN, 'deleted' => User::STATUS_DELETED])->one();
            if (empty($user)) {
                Yii::$app->session->setFlash('danger', 'Data Not Found!');
                return $this->redirect(['/user/profile', 'slug' => $slug]);
            }
        }

        // Find and Validate Profile Data
        $profile = Profile::find()->andWhere('user_id = :uId and status = :active', ['uId' => $user->id, 'active' => Profile::STATUS_ACTIVE])->one();
        if (!empty($profile)) {
            Yii::$app->session->setFlash('info', 'Additional Information Already Exist!');
            return $this->redirect(['/user/profile', 'slug' => $slug]);
        }

        $model = new Profile();
        if ($model->load(Yii::$app->request->post())) {
            // Profile Information
            $model->slug = Yii::$app->BtsystemComponent->slugGenerator();
            $model->user_id = $user->id;
            $model->mobile = (empty($model->mobile) ? '' : "+91{$model->mobile}");
            $model->updated_by = Yii::$app->user->getId();
            $model->created_by = Yii::$app->user->getId();
            if (!$model->save()) {
                Yii::$app->session->setFlash('danger', 'Fail To Add Additional Information!');
                return $this->refresh();
            }

            Yii::$app->session->setFlash('success', 'Additional Information Added!');
            return $this->redirect(['/user/profile', 'slug' => $slug]);
        }

        $context = [
            'model' => $model,
        ];
        return $this->render('add', $context);
    }

    /**
     * Update
     * ======================================
     * 
     * @param $slug app\models\Profile
     */
    public function actionUpdate($slug = null)
    {
        // Find and Validate User Data
        if (empty($slug) || $slug == null) {
            // If slug is empty
            $user = User::find()->andWhere('id = :uId and status = :active', ['uId' => Yii::$app->user->getId(), 'active' => User::STATUS_ACTIVE])->one();
            if (empty($user)) {
                Yii::$app->session->setFlash('danger', 'Data Not Found!');
                return $this->redirect(['/user/profile']);
            }
        } else {
            // If Slug is not empty
            $user = User::find()->andWhere('slug = :uSlug and role != :superAdmin and status != :deleted', ['uSlug' => $slug, 'superAdmin' => User::ROLE_SUPERADMIN, 'deleted' => User::STATUS_DELETED])->one();
            if (empty($user)) {
                Yii::$app->session->setFlash('danger', 'Data Not Found!');
                return $this->redirect(['/user/profile', 'slug' => $slug]);
            }
        }

        // Find and Validate Profile Data
        $profile = Profile::find()->andWhere('user_id = :uId and status = :active', ['uId' => $user->id, 'active' => Profile::STATUS_ACTIVE])->one();
        if (empty($profile)) {
            Yii::$app->session->setFlash('info', 'Data Not Found!');
            return $this->redirect(['/user/profile', 'slug' => $slug]);
        }

        // After Form Submit
        if (Yii::$app->request->isPost) {
            if ($profile->load(Yii::$app->request->post())) {
                // Profile Information
                $profile->mobile = (empty($profile->mobile) ? '' : (strlen($profile->mobile) == 13 ? $profile->mobile : "+91{$profile->mobile}"));
                $profile->updated_by = Yii::$app->user->getId();

                if (!$profile->update(true)) {
                    Yii::$app->session->setFlash('danger', 'Fail To Update!');
                    return $this->refresh();
                }

                Yii::$app->session->setFlash('success', 'Additional Information Updated!');
                return $this->redirect(['/user/profile', 'slug' => $slug]);
            }
        }

        $profile->mobile = (empty($profile->mobile) ? '' : substr($profile->mobile, 3));
        $context = [
            'profile' => $profile,
        ];
        return $this->render('update', $context);
    }
}
