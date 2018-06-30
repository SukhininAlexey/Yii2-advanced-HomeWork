<?php

namespace frontend\controllers;

use common\models\task\Task;
use common\models\task\TaskSearch;
/**
 * Description of TaskController
 *
 * @author Lex
 */
class TaskController extends \yii\web\Controller {
    
    public function actionIndex(){

        // Получаем пользователя и проверяем авторизацию
        $user_id = \Yii::$app->user->id;
        if(!$user_id){
            return $this->render('_notLoggedIn');
        }
        
        // Получаю из сессии язык, чтобы правильно её настроить
        \Yii::$app->language =  \Yii::$app->session->get('lang') ?? 'en-GB';
        
        // Получаем месяц-год с предварительной установкой верного времени
        date_default_timezone_set('Europe/Moscow'); // Постоянно съезжает на час. В рунете говорят, что из-за переводов часов.
        if($date = \Yii::$app->request->post('date')){
            $year = mb_substr($date, 0, 4);
            $month = mb_substr($date, 5, 2);
            //var_dump(\Yii::$app->request->post('date')); exit;
        }else{
            $date = date("Y-m-d", time());
            $year = date('Y');
            $month = date('m');
        }
        
        
        $cache = \Yii::$app->cache;
        $key = 'calendar_' . $user_id;
        
        $dependency = new \yii\caching\DbDependency();
        $dependency->sql = "SELECT count(*) from tasks WHERE user_id = :user_id";
        $dependency->params = [':user_id' => $user_id];
        
        if(!$tasksResult = $cache->get($key)){
            
            $tasksResult = \common\models\task\Task::findAll(['user_id' => $user_id]);
            $cache->set($key, $tasksResult, 180, $dependency);
        }
        
        $calendar = array_fill_keys(range(1, date('t', strtotime($date))), []);

        foreach ($tasksResult as $index => $task) {
            $pattern = "/^{$year}-{$month}-/";
            if(preg_match($pattern, $task->date)){
                array_push($calendar[date("j", strtotime($task->date))], $task);
            }
        }
        
        return $this->render('index', [
            'calendar' => $calendar,
        ]);
    }
    
    
    
    
    public function actionSingle($taskId = null){
        $user_id = \Yii::$app->user->id;
        if(!$user_id){
            return $this->render('_notLoggedIn');
        }
        
        // Получаю из сессии язык, чтобы правильно её настроить
        \Yii::$app->language =  \Yii::$app->session->get('lang') ?? 'en-GB';
        
        $task_id = $taskId ? $taskId : \Yii::$app->request->get('taskId');
        if(!$task_id){
            return $this->render('_err404');
        }
        
        $picture = new \common\models\task\CommentPic();
        $comment = new \common\models\task\Comment();
        
        if (\Yii::$app->request->isPost) {
            $comment = new \common\models\task\Comment();
            date_default_timezone_set('Europe/Moscow');
            $comment->content = \Yii::$app->request->post('Comment')['content'];
            $comment->user_id = $user_id;
            $comment->task_id = $task_id;
            $comment->date = date("Y-m-d H:i:s");
            $comment->save();
            
            $comment->images = \yii\web\UploadedFile::getInstances($comment, 'images');
            $comment->uploadImages();

        }
        
        $task = \common\models\task\Task::findOne($task_id);
        $comments = \common\models\task\Comment::findAllWithUser($task_id);
        $user = \common\models\User::findOne(['id' => $task->user_id]);

        
        return $this->render('single', ['model' => $task, 'comments' => $comments, 'user' => $user, 'comment' => $comment]);
    }
    
    public function actionCreate(){
        
        $model = new \common\models\task\Task();
        $model->setScenario(\common\models\task\Task::SCENARIO_ADMIN);
        
        
        $modelInsertHandler = function($event){
            $user = \common\models\task\User::findOne($event->sender->user_id);
            $contacter = new \common\models\Contacter([
                'name' => 'Ваш Любимый Начальник',
                'subject' => 'Новое задание: ' . $event->sender->name,
                'email' =>  'nachalnik@thegreat.org',
                'body' => 'На вас повесили новый таск: ' . $event->sender->description . 'Выполнить до ' . $event->sender->date,
            ]);
            
            if($contacter->contact($user->email)){
                echo "Сообщение отправлено пользователю под логином {$user->login} на действие '{$event->sender->name}'"; //exit;
            }else{
                echo "Не отправлено";
            }
        };
        
        
        $model->on($model::EVENT_AFTER_INSERT, $modelInsertHandler);
        
        if($model->load(\Yii::$app->request->post()) && $model->save()){
            $this->redirect(['admin-task/index']);
        }

        return $this->render('create', ['model' => $model]);
    }
    
    
    // Обслуживает интересы actionView
    protected function findModel($id)
    {
        if (($model = \common\models\task\Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
