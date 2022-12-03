<?php

namespace app\controllers;

use app\models\Team;
use app\models\TeamMember;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class TeamController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'add', 'update', 'view', 'status', 'delete', 'add-team-member', 'change-status', 'remove'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['add', 'update', 'status'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            // Status Validation
                            if (Yii::$app->user->identity->status === User::STATUS_INACTIVE || Yii::$app->user->identity->status === User::STATUS_DELETED) {
                                return $this->redirect(['/user/force-logout']);
                            }

                            // Role Validation - Only Admin is allowed.
                            if (Yii::$app->user->identity->role !== User::ROLE_ADMIN) {
                                return $this->redirect(['/site']);
                            }

                            return true;
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            // Status Validation
                            if (Yii::$app->user->identity->status === User::STATUS_INACTIVE || Yii::$app->user->identity->status === User::STATUS_DELETED) {
                                return $this->redirect(['/user/force-logout']);
                            }

                            // Role Validation - Super-admin and Admin is allowed.
                            if (Yii::$app->user->identity->role !== User::ROLE_SUPERADMIN || Yii::$app->user->identity->role !== User::ROLE_ADMIN) {
                                return $this->redirect(['/site']);
                            }

                            return true;
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'add-team-member', 'change-status', 'remove'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            // Status Validation
                            if (Yii::$app->user->identity->status === User::STATUS_INACTIVE || Yii::$app->user->identity->status === User::STATUS_DELETED) {
                                return $this->redirect(['/user/force-logout']);
                            }

                            // Role Validation - Super-admin, Admin and Team Leader are allowed.
                            if (Yii::$app->user->identity->role == User::ROLE_DEVELOPER || Yii::$app->user->identity->role == User::ROLE_TESTER) {
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
                    'delete' => ['post'],
                    'remove' => ['post'],
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
        if (Yii::$app->user->identity->role == User::ROLE_TEAM_LEADER) {
            $teams = Team::find()->andWhere('team_leader_id = :teamLeader and status != :deleted', ['teamLeader' => Yii::$app->user->getId(), 'deleted' => Team::STATUS_DELETED])->all();
        } else {
            $teams = Team::find()->andWhere('status != :deleted', ['deleted' => Team::STATUS_DELETED])->all();
        }

        $context = [
            'teams' => $teams,
        ];
        return $this->render('index', $context);
    }

    /**
     * Add
     * ====================================
     */
    public function actionAdd()
    {
        $model = new Team();
        if ($model->load(Yii::$app->request->post())) {
            // Team Information
            $model->slug = Yii::$app->BtsystemComponent->slugGenerator($model->title);
            $model->updated_by = Yii::$app->user->getId();
            $model->created_by = Yii::$app->user->getId();
            if (!$model->save()) {
                Yii::$app->session->setFlash('danger', 'Fail To Create Team!');
                return $this->redirect(['/team/index']);
            }

            Yii::$app->session->setFlash('success', 'New Team Created!');
            return $this->redirect(['/team/index']);
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
     * @param $slug app\models\Team
     */
    public function actionUpdate($slug = null)
    {
        // Check Param
        if (empty($slug) || $slug == null) {
            Yii::$app->session->setFlash('danger', 'Missing Required Information!');
            return $this->redirect(['index']);
        }

        // Find and Validate Team Data
        $team = Team::find()->andWhere('slug = :uSlug and status != :deleted', ['uSlug' => $slug, 'deleted' => Team::STATUS_DELETED])->one();
        if (empty($team)) {
            Yii::$app->session->setFlash('danger', 'Data Not Found!');
            return $this->redirect(['/team/index']);
        }

        // After Form Submit
        if ($team->load(Yii::$app->request->post())) {
            // Team Information
            $team->updated_by = Yii::$app->user->getId();
            if (!$team->update(true)) {
                Yii::$app->session->setFlash('danger', 'Fail To Update!');
                return $this->refresh();
            }

            Yii::$app->session->setFlash('success', 'Team Updated!');
            return $this->redirect(['index']);
        }

        $context = [
            'team' => $team,
        ];
        return $this->render('update', $context);
    }

    /**
     * View
     * ====================================
     * If current user is a Team-leader then view only it's assign team.
     * If current user is a Super-admin or Admin than view all the available team.
     * 
     * @param $slug app\models\Team
     */
    public function actionView($slug = null)
    {
        // Check Param
        if (empty($slug) || $slug == null) {
            Yii::$app->session->setFlash('danger', 'Missing Required Information!');
            return $this->redirect(['index']);
        }

        // Find and Validate User Data
        if (Yii::$app->user->identity->role === User::ROLE_TEAM_LEADER) {
            // If User is Team Leader
            $team = Team::find()->andWhere('slug = :uSlug and team_leader_id = :teamLeader and status != :deleted', ['uSlug' => $slug, 'teamLeader' => Yii::$app->user->getId(), 'deleted' => Team::STATUS_DELETED])->one();
            if (empty($team)) {
                Yii::$app->session->setFlash('danger', 'Data Not Found!');
                return $this->redirect(['index']);
            }
        } else {
            // If user is Super-admin or Admin
            $team = Team::find()->andWhere('slug = :uSlug and status != :deleted', ['uSlug' => $slug, 'deleted' => Team::STATUS_DELETED])->one();
            if (empty($team)) {
                Yii::$app->session->setFlash('danger', 'Data Not Found!');
                return $this->redirect(['index']);
            }
        }

        // Find Team Member Data
        $team_members = TeamMember::find()->andWhere('team_id = :team and status != :deleted', ['team' => $team->id, 'deleted' => TeamMember::STATUS_DELETED])->all();

        // All User That are not in team
        $db = Yii::$app->getDb();
        $query = $db->createCommand("
            SELECT u.id, u.name 
            FROM user u LEFT JOIN team_member tm 
            ON u.id = tm.user_id
            WHERE (tm.user_id IS NULL AND (u.role = 3 OR u.role = 4) AND u.status = 1)
            OR (tm.status = 2 AND (u.role = 3 OR u.role = 4) AND u.status = 1)
        ");
        $users = $query->queryAll();

        $context = [
            'team' => $team,
            'team_members' => $team_members,
            'users' => $users,
        ];
        return $this->render('view', $context);
    }

    /**
     * Status
     * ====================================
     * 
     * @param $slug app\models\Team
     */
    public function actionStatus($slug = null)
    {
        // Check Param
        if (empty($slug) || $slug == null) {
            Yii::$app->session->setFlash('danger', 'Missing Required Information!');
            return $this->redirect(['index']);
        }

        // Find and Validate Team Data
        $team = Team::find()->andWhere('slug = :uSlug and status != :deleted', ['uSlug' => $slug, 'deleted' => Team::STATUS_DELETED])->one();
        if (empty($team)) {
            Yii::$app->session->setFlash('danger', 'Data Not Found!');
            return $this->redirect(['/team/index']);
        }

        // Team Information
        $team->status = ($team->status === Team::STATUS_ACTIVE ? Team::STATUS_INACTIVE : Team::STATUS_ACTIVE);
        if (!$team->update(true)) {
            Yii::$app->session->setFlash('danger', 'Fail To Change Status!');
            return $this->redirect(['index']);
        }

        Yii::$app->session->setFlash('success', 'Status Changed!');
        return $this->redirect(['index']);
    }

    /**
     * Delete
     * ====================================
     */
    public function actionDelete()
    {
        if(Yii::$app->request->isPost) {
            $datas = Yii::$app->request->post();

            // Check Param
            if(empty($datas['team_slug'])) {
                Yii::$app->session->setFlash('danger', 'Missing Required Information');
                return $this->redirect('index');
            }

            // Find and Validate Team Data
            $team = Team::find()->andWhere('slug = :uSlug and status != :deleted', ['uSlug' => $datas['team_slug'], 'deleted' => Team::STATUS_DELETED])->one();
            if(empty($team)) {
                Yii::$app->session->setFlash('danger', 'Data Not Found!');
                return $this->redirect(['index']);
            }

            // Team Information
            $team->status = Team::STATUS_DELETED;
            if(!$team->update(true)) {
                Yii::$app->session->setFlash('danger', 'Fail To Delete Team!');
            } else {
                Yii::$app->session->setFlash('success', 'Team Deleted!');
            }

            return $this->redirect(['index']);
        }
    }

    /**
     * Add Team Member
     * ====================================
     */
    public function actionAddTeamMember()
    {
        if (Yii::$app->request->isPost) {
            $datas = Yii::$app->request->post();

            // Check Param
            if (empty($datas['teamLeader']) || empty($datas['selectMember']) || empty($datas['teamSlug'])) {
                Yii::$app->session->setFlash('danger', 'Missing Required Information!');
                return $this->redirect(['index']);
            }

            // Find and Validate Team Member Data
            $teammember = TeamMember::find()->andWhere('team_id = :teamLeader and user_id = :member and status = :deleted', ['teamLeader' => $datas['teamLeader'], 'member' => $datas['selectMember'], 'deleted' => TeamMember::STATUS_DELETED])->one();
            try {
                if (empty($teammember)) {
                    $member = new TeamMember([
                        'team_id' => $datas['teamLeader'],
                        'user_id' => $datas['selectMember'],
                        'updated_by' => Yii::$app->user->getId(),
                        'created_by' => Yii::$app->user->getId(),
                    ]);
                    $member->save();
                } else {
                    $teammember->status = TeamMember::STATUS_ACTIVE;
                    $teammember->update(true);
                }    
    
                Yii::$app->session->setFlash('success', 'Team Member Added!');
                return $this->redirect(['view', 'slug' => $datas['teamSlug']]);
            } catch(\Exception $e) {
                Yii::$app->session->setFlash('danger', 'Fail To Add Member!');
                return $this->redirect(['view', 'slug' => $datas['teamSlug']]);
            }
        }
    }

    /**
     * Change Status
     * ====================================
     * 
     * @param $id app\Model\TeamMember
     */
    public function actionChangeStatus($slug = null, $id = null)
    {
        // Check Param
        if((empty($slug) || $slug == null) || (empty($id) || $id == null)) {
            Yii::$app->session->setFlash('danger', 'Missing Required Information!');
            return $this->redirect(['index']);
        }

        // Find and Validate Team Member Data
        $teammember = TeamMember::find()->andWhere('id = :uId and status != :deleted', ['uId' => $id, 'deleted' => TeamMember::STATUS_DELETED])->one();
        if(empty($teammember)) {
            Yii::$app->session->setFlash('danger', 'Data Not Found!');
            return $this->redirect(['view', 'slug' => $slug]);
        }

        // Team Member Information
        $teammember->status = ($teammember->status == TeamMember::STATUS_ACTIVE ? TeamMember::STATUS_INACTIVE : TeamMember::STATUS_ACTIVE);
        $teammember->updated_by = Yii::$app->user->getId();
        if($teammember->update(true)) {
            Yii::$app->session->setFlash('success', 'Status Changed!');
        } else {
            Yii::$app->session->setFlash('danger', 'Fail To Change Status!');
        }
        
        return $this->redirect(['view', 'slug' => $slug]);
    }

    /**
     * Remove
     * ====================================
     * Function to remove team member from team member list
     */
    public function actionRemove()
    {
        if (Yii::$app->request->isPost) {
            $datas = Yii::$app->request->post();

            // Check Param
            if (empty($datas['team_id']) || empty($datas['team_slug'])) {
                Yii::$app->session->setFlash('danger', 'Missing Required Information!');
                return $this->redirect(['index']);
            }

            // Find and Validate Team Member Data
            $teamMember = TeamMember::find()->andWhere('id = :uId and status != :deleted', ['uId' => $datas['team_id'], 'deleted' => TeamMember::STATUS_DELETED])->one();
            if (empty($teamMember)) {
                Yii::$app->session->setFlash('danger', 'Data Not Found!');
                return $this->redirect(['view', 'slug' => $datas['team_slug']]);
            }

            // Team Member Information
            $teamMember->status = TeamMember::STATUS_DELETED;
            if (!$teamMember->update(true)) {
                Yii::$app->session->setFlash('danger', 'Fail To Remove Team Member');
            } else {
                Yii::$app->session->setFlash('success', 'Team Member Removed!');
            }

            return $this->redirect(['view', 'slug' => $datas['team_slug']]);
        }
    }
}
