<?php 

namespace app\controllers;

use app\models\Project;
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
     * ====================================
     */
    public function actionIndex()
    {
        $projects = Project::find()->andWhere('status != :deleted', ['deleted' => Project::STATUS_DELETED])->all();
        
    }
}