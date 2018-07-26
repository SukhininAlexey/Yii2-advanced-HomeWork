<?php

namespace frontend\controllers;

use Yii;
use common\models\task\Task;
use frontend\models\task\TaskSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Member;
use yii\helpers\ArrayHelper;
use common\models\Project;
use yii\filters\AccessControl;
use common\models\task\TaskStatus;
use yii\web\ForbiddenHttpException;

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
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['checkTask']
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
        $permissions = $task->getPermissions();
        if(!$permissions['view']){
            throw new ForbiddenHttpException('This page is forbidden for you');
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
            'permissions' => $permissions,
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
        $permissions = $project->getPermissions();
        if(!$permissions['addTask']){
            throw new ForbiddenHttpException('This page is forbidden for you');
        }
        
        $model = new Task();
        
        $currentUserArr = Member::getProjCurrentUserArr($project_id);
        $currentUserNames = ArrayHelper::map($currentUserArr, 'id', 'username');
        
        
        
        if ($model->load(Yii::$app->request->post())){
            
            // Id проекта жестко определен. Сначала подгружаем данные, потом подменяем целевые значения
            $model->project_id = $project_id;
            $model->leader_id = Yii::$app->user->id;
            
            if($model->save() && ArrayHelper::keyExists($model->user_id, $currentUserNames)){
                return $this->redirect(['project/view', 'id' => $project_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'currentUserNames' => $currentUserNames,
            'permissions' => $permissions,
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
    public function actionUpdate($id)
    {
        
        $model = $this->findModel($id);
        $project_id = $model->project_id;
        $permissions = $model->getPermissions();
        
        // Проверка на лидерство над проектом, , за которым закреплено действие
        if(!$permissions['update']){
            throw new ForbiddenHttpException('This page is forbidden for you');
        }
        
        $currentUserArr = Member::getProjCurrentUserArr($project_id);
        
        $currentUserNames = ArrayHelper::map($currentUserArr, 'id', 'username');
        
        

        if ($model->load(Yii::$app->request->post())) {
            
            // Важно, чтобы project_id не изменился при редактировании
            $model->project_id = $project_id;
            if($model->save() && ArrayHelper::keyExists($model->user_id, $currentUserNames)){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        
        $statuses = TaskStatus::getStatusesAssocArr();
        
        return $this->render('update', [
            'model' => $model,
            'currentUserNames' => $currentUserNames,
            'permissions' => $permissions,
            'statuses' => $statuses,
        ]);
    }
    
    public function actionResolve($id)
    {
        
        $model = $this->findModel($id);
        $project_id = $model->project_id;
        $permissions = $model->getPermissions();
        
        // Проверка на лидерство над проектом, , за которым закреплено действие
        if(!$permissions['resolve']){
            throw new ForbiddenHttpException('This page is forbidden for you');
        }
        
        $currentUserArr = Member::getProjCurrentUserArr($project_id);
        $currentUserNames = ArrayHelper::map($currentUserArr, 'id', 'username');
        
        if($modelNew = Yii::$app->request->post('Task')){
            
            $model->status_id = $modelNew['status_id'];
            if ($model->save() && ArrayHelper::keyExists($model->user_id, $currentUserNames)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        
        $statuses = TaskStatus::getStatusesAssocArr();

        return $this->render('resolve', [
            'model' => $model,
            'currentUserNames' => $currentUserNames,
            'permissions' => $permissions,
            'statuses' => $statuses,
        ]);
    }
    
    public function actionDelete()
    {
        
        $project_id = Yii::$app->request->post('project_id');
        $task = findModel(Yii::$app->request->post('task_id'));
        $permissions = $task->getPermissions();
        
        // Проверка на лидерство над проектом
        if(!$permissions['delete']){
            throw new ForbiddenHttpException('This page is forbidden for you');
        }
        
        $task->delete();

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
