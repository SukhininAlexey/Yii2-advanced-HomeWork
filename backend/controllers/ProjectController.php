<?php

namespace backend\controllers;

use Yii;
use common\models\Project;
use backend\models\ProjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\models\task\TaskSearch;

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
        $member = \common\models\Member::findOne(['user_id' => Yii::$app->user->id, 'project_id' => $id]);
        if($project->leader_id != Yii::$app->user->id && $member == NULL){
            return $this->redirect(['project/index', 'id' => $id]);
        }
        
        $actionSearchModel = new TaskSearch();
        $actionDataProvider = $actionSearchModel->search(Yii::$app->request->queryParams, $id);
        
        $userSearchModel = new \backend\models\TeamSearch();
        $userDataProvider = $userSearchModel->search(Yii::$app->request->queryParams, $id);
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'actionSearchModel' => $actionSearchModel,
            'actionDataProvider' => $actionDataProvider,
            'userSearchModel' => $userSearchModel,
            'userDataProvider' => $userDataProvider,
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
        
        $model = new Project();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
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
        if($model->leader_id != Yii::$app->user->id){
            return $this->redirect(['project/view', 'id' => $id]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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
        if($project->leader_id != Yii::$app->user->id){
            return $this->redirect(['project/view', 'id' => $id]);
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
