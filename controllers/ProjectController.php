<?php

namespace app\controllers;

use app\models\Project;
use app\models\User;
use app\models\Team;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ProjectController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'add', 'update', 'view', 'change-status', 'deleted'],
                'rules' => [
                    [
                        'actions' => ['index', 'view',],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            // Status Validation
                            if (Yii::$app->user->identity->status === User::STATUS_INACTIVE || Yii::$app->user->identity->status === User::STATUS_DELETED) {
                                return $this->redirect(['/user/force-logout']);
                            }

                            // Role Validation - Super-admin, Admin and Team Leader are allowed.
                            if (Yii::$app->user->identity->role !== User::ROLE_SUPERADMIN && Yii::$app->user->identity->role !== User::ROLE_ADMIN && Yii::$app->user->identity->role !== User::ROLE_TEAM_LEADER) {
                                return $this->redirect(['/site']);
                            }

                            return true;
                        }
                    ],
                    [
                        'actions' => ['add', 'update', 'change-status', 'deleted'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            // Status Validation
                            if (Yii::$app->user->identity->status === User::STATUS_INACTIVE || Yii::$app->user->identity->status === User::STATUS_DELETED) {
                                return $this->redirect(['/user/force-logout']);
                            }

                            // Role Validation - Admin is allowed.
                            if (Yii::$app->user->identity->role !== User::ROLE_ADMIN) {
                                return $this->redirect(['/site']);
                            }

                            return true;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'deleted' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Index
     * ====================================
     */
    public function actionIndex()
    {
        // Find Project Data
        $projects = Project::find()->andWhere('status != :deleted', ['deleted' => Project::STATUS_DELETED])->all();

        $context = [
            'projects' => $projects
        ];
        return $this->render('index', $context);
    }

    /**
     * Add
     * ====================================
     */
    public function actionAdd()
    {
        $model = new Project();
        if ($model->load(Yii::$app->request->post())) {
            // Default Information
            $model->slug = Yii::$app->BtsystemComponent->slugGenerator($model->title);
            $model->updated_by = Yii::$app->user->getId();
            $model->created_by = Yii::$app->user->getId();
            if (!$model->save()) {
                Yii::$app->session->setFlash('danger', 'Fail To Add New Project!');
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', 'New Project Added!');
                return $this->redirect(['index']);
            }
        }

        $context = [
            'model' => $model,
        ];
        return $this->render('add', $context);
    }

    /**
     * Update
     * ====================================
     * 
     * @param $slug app\Models\Project
     */
    public function actionUpdate($slug = null)
    {
        // Check Param
        if (empty($slug)) {
            Yii::$app->session->setFlash('danger', 'Missing Required Information!');
            return $this->redirect(['index']);
        }

        // Find and Validate Project Data
        $project = Project::find()->andWhere('slug = :uSlug and status != :deleted', ['uSlug' => $slug, 'deleted' => Project::STATUS_DELETED])->one();
        if (empty($project)) {
            Yii::$app->session->setFlash('danger', 'Data Not Found!');
            return $this->redirect(['index']);
        }

        // After Submit
        if ($project->load(Yii::$app->request->post())) {
            // Default Information
            $project->updated_by = Yii::$app->user->getId();
            if (!$project->update(true)) {
                Yii::$app->session->setFlash('daner', 'Fail To Update!');
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', 'Project Updated!');
                return $this->redirect(['index']);
            }
        }

        $context = [
            'project' => $project,
        ];
        return $this->render('update', $context);
    }

    /**
     * Change Status
     * ====================================
     * 
     * @param $slug app\Models\Project
     */
    public function actionChangeStatus($slug = null)
    {
        // Check Param
        if (empty($slug)) {
            Yii::$app->session->setFlash('danger', 'Missing Required Information!');
            return $this->redirect(['index']);
        }

        // Find and Validate Project Data
        $project = Project::find()->andWhere('slug = :uSlug and status != :deleted', ['uSlug' => $slug, 'deleted' => Project::STATUS_DELETED])->one();
        if (empty($project)) {
            Yii::$app->session->setFlash('danger', 'Data Not Found!');
            return $this->redirect(['index']);
        }

        // Update Project
        $project->status = ($project->status === Project::STATUS_ACTIVE ? Project::STATUS_INACTIVE : Project::STATUS_ACTIVE);
        $project->updated_by = Yii::$app->user->getId();
        if (!$project->update(true)) {
            Yii::$app->session->setFlash('danger', 'Fail To Change Status!');
        } else {
            Yii::$app->session->setFlash('success', 'Status Changed!');
        }

        return $this->redirect(['index']);
    }

    /**
     * View
     * ====================================
     * 
     * @param $slug app\Models\Project
     */
    public function actionView($slug = null)
    {
        // Check Param
        if (empty($slug)) {
            Yii::$app->session->setFlash('danger', 'Missing Required Information!');
            return $this->redirect(['index']);
        }

        // Find and Validate Project Data
        $project = Project::find()->andWhere('slug = :uSlug and status != :deleted', ['uSlug' => $slug, 'deleted' => Project::STATUS_DELETED])->one();
        if (empty($project)) {
            Yii::$app->session->setFlash('danger', 'Data Not Found!');
            return $this->redirect(['index']);
        } 
        
        // If user is team-leader than it should be assign to that project
        if(Yii::$app->user->identity->role == User::ROLE_TEAM_LEADER) {
            if($project->teams[0]->team_leader_id != Yii::$app->user->getId()) {
                Yii::$app->session->setFlash('danger', 'Unauthorized Access!');
                return $this->redirect(['index']);
            }
        }

        $context = [
            'project' => $project,
        ];
        return $this->render('view', $context);
    }

    /**
     * Deleted
     * ====================================
     */
    public function actionDeleted()
    {
        if (Yii::$app->request->isPost) {
            $datas = Yii::$app->request->post();

            // Check Param
            if (empty($datas['project_slug'])) {
                Yii::$app->session->setFlash('danger', 'Missing Required Information!');
                return $this->redirect(['index']);
            }

            // Find and Validate Project Data
            $project = Project::find()->andWhere('slug = :uSlug and status != :deleted', ['uSlug' => $datas['project_slug'], 'deleted' => Project::STATUS_DELETED])->one();
            if (empty($project)) {
                Yii::$app->session->setFlash('danger', 'Data Not Found!');
                return $this->redirect(['index']);
            }

            // Project Information
            $project->status = Project::STATUS_DELETED;
            $project->updated_by = Yii::$app->user->getId();
            if (!$project->update(true)) {
                Yii::$app->session->setFlash('danger', 'Fail To Update!');
            } else {
                Yii::$app->session->setFlash('success', 'Project Deleted!');
            }

            return $this->redirect(['index']);
        }
    }
}
