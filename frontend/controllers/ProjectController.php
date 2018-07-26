<?php

namespace frontend\controllers;

use Yii;
use common\models\Project;
use frontend\models\ProjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

use frontend\models\task\TaskSearch;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
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
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['checkTeam']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // Проверка на причастность к проекту
        $project = \common\models\Project::findOne($id);
        $permissions = $project->getPermissions();
        if(!$permissions['view']){
            throw new ForbiddenHttpException('This page is forbidden for you');
        }
        
        $actionSearchModel = new TaskSearch();
        $actionDataProvider = $actionSearchModel->search(Yii::$app->request->queryParams, $id);
        
        $userSearchModel = new \frontend\models\TeamSearch();
        $userDataProvider = $userSearchModel->search(Yii::$app->request->queryParams, $id);
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'actionSearchModel' => $actionSearchModel,
            'actionDataProvider' => $actionDataProvider,
            'userSearchModel' => $userSearchModel,
            'userDataProvider' => $userDataProvider,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // Нужна проверка уровня доступа
        if(!Yii::$app->user->can('createTeam')){
            throw new ForbiddenHttpException('This page is forbidden for you');
        }
        
        $model = new Project();

        if ($model->load(Yii::$app->request->post())) {
            
            $model->leader_id = Yii::$app->user->id;
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {        
        // Проверка на лидерство над проектом
        $model = $this->findModel($id);
        $leader_id = $model->leader_id;
        $permissions = $model->getPermissions();
        if(!$permissions['update']){
            throw new ForbiddenHttpException('This page is forbidden for you');
        }

        if ($model->load(Yii::$app->request->post())) {
            
            $model->leader_id = $leader_id;
            $model->id = $id;
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // Проверка на лидерство над проектом
        $project = $this->findModel($id);
        $permissions = $project->getPermissions();
        if(!$permissions['delete']){
            throw new ForbiddenHttpException('This page is forbidden for you');
        }
        
        $project->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
