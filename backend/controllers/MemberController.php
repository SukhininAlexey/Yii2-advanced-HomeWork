<?php

namespace backend\controllers;

use Yii;
use common\models\Member;
use backend\models\MemberSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\User;
use yii\web\ForbiddenHttpException;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class MemberController extends Controller
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
     * Lists all Member models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Member model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Member model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($project_id)
    {
        
        // Проверка на лидерство над проектом
        $project = \common\models\Project::findOne($project_id);
        $permissions = $project->getPermissions();
        if(!$permissions['addUser']){
            throw new ForbiddenHttpException('This page is forbidden for you');
        }
        
        $model = new Member();
        
        $possibleUserArr = Member::getProjPossibleUsersArr($project_id);
        $possibleUserNames = ArrayHelper::map($possibleUserArr, 'id', 'username');
        
        if ($model->load(Yii::$app->request->post())) {
            
            // Id проекта жестко определен
            $model->project_id = $project_id;
            if($model->save() && ArrayHelper::keyExists($model->user_id, $possibleUserNames)){
                return $this->redirect(['project/view', 'id' => $project_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'possibleUserNames' => $possibleUserNames,
        ]);
    }

    /**
     * Updates an existing Member model.
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
     * Deletes an existing Member model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAdminelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionDelete()
    {
        $project_id = Yii::$app->request->post('project_id');
        
        // Проверка на лидерство над проектом
        $project = \common\models\Project::findOne($project_id);
        $permissions = $project->getPermissions();
        if(!$permissions['expelUser']){
            throw new ForbiddenHttpException('This page is forbidden for you');
        }
        
        $user_id =  Yii::$app->request->post('user_id');
        $member = Member::findOne(['user_id' => $user_id, 'project_id' => $project_id]);
        
        $member->delete();
        return $this->redirect(['project/view', 'id' => $project_id]);
    }

    /**
     * Finds the Member model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
