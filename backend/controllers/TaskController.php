<?php

namespace backend\controllers;

use Yii;
use common\models\task\Task;
use backend\models\task\TaskSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Member;
use yii\helpers\ArrayHelper;
use common\models\Project;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
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
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // Проверка на ПРИЧАСТНОСТЬ к действию
        $task = $this->findModel($id);
        $hostUserId = Yii::$app->user->id;
        $project = Project::findOne($task->project_id);
        $member = Member::findOne([
            'project_id' => $task->project_id, 
            'user_id' => $hostUserId
        ]);
        if(
                $task->leader_id != $hostUserId 
                && $task->user_id != $hostUserId
                && $project->leader_id != $hostUserId
                && $member == null){
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        // Получаю необходимую информацию
        $url = \yii\helpers\Url::to();
        $task = $this->findModel($id);
        $entity = [
            'task' => $task,
            'url' => $url,
        ];
        
        // Хранить всё буду в сессии
        $session = Yii::$app->session;
        
        // Проверяю, создан ли ключ и создаю его, если нужно
        if(!isset($session['lastUrls'])){
            $session['lastUrls'] = [];
        }
        
        $lastUrls = $session->get('lastUrls');
        
        // Добавляю новую запись в начало массива
        array_unshift($lastUrls, $entity);
        
        // Удаляю лишнние последние записи в конце
        if(count($lastUrls)>5) {
            array_splice($lastUrls, 5);
        }
        
        // Обновляю ключи и сохраняю в сессию
        $session['lastUrls'] = array_values($lastUrls);
        
        return $this->render('view', [
            'model' => $task,
        ]);
    }
    

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($project_id)
    {
        
        // Проверка на лидерство над проектом
        $project = \common\models\Project::findOne($project_id);
        if($project->leader_id != Yii::$app->user->id){
            return $this->redirect(['project/view', 'id' => $project_id]);
        }
        
        $model = new Task();
        
        $currentUserArr = Member::getProjCurrentUserArr($project_id);
        
        $currentUserNames = ArrayHelper::map($currentUserArr, 'id', 'username');
        
        // Id проекта жестко определен
        $model->project_id = $project_id;
        $model->leader_id = Yii::$app->user->id;
        
        if (
                $model->load(Yii::$app->request->post()) 
                && $model->save() 
                && ArrayHelper::keyExists($model->user_id, $currentUserNames
            )) {

            return $this->redirect(['project/view', 'id' => $project_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'currentUserNames' => $currentUserNames,
        ]);
    }
    
    public function actionAdmincreate(){
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($task_id, $project_id)
    {
        // Проверка на лидерство над проектом
        
        $project = \common\models\Project::findOne($project_id);
        if($project->leader_id != Yii::$app->user->id){
            return $this->redirect(['project/view', 'id' => $project_id]);
        }
        
        $model = $this->findModel($task_id);
        
        $currentUserArr = Member::getProjCurrentUserArr($project_id);
        
        $currentUserNames = ArrayHelper::map($currentUserArr, 'id', 'username');
        
        

        if ($model->load(Yii::$app->request->post()) && $model->save() && ArrayHelper::keyExists($model->user_id, $currentUserNames)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'currentUserNames' => $currentUserNames
        ]);
    }

    
    
    public function actionDelete()
    {
        $project_id = Yii::$app->request->post('project_id');
        // Проверка на лидерство над проектом
        $project = \common\models\Project::findOne($project_id);
        if($project->leader_id != Yii::$app->user->id){
            return $this->redirect(['project/view', 'id' => $project_id]);
        }
        
        $task_id =  Yii::$app->request->post('task_id');
        
        $this->findModel($task_id)->delete();

        return $this->redirect(['project/view', 'id' => $project_id]);
    }
     
    
    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAdmindelete($id)
    {
        \yii\web\YiiAsset::register($this);
        $this->findModel($task_id)->delete();

        return $this->redirect(['project/index']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
