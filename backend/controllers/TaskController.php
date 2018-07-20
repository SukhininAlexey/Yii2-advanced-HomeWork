<?php

namespace backend\controllers;

use Yii;
use common\models\task\Task;
use backend\models\task\TaskSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
    
    public function actionTest()
    {
        var_dump(Yii::$app->session->get('lastUrls')); exit;
    }
    
    public function actionRemove()
    {
        var_dump(Yii::$app->session->remove('lastUrls')); exit;
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
