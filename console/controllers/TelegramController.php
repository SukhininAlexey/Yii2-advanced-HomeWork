<?php


namespace console\controllers;

use yii\console\Controller;
use \common\models\tables\TelegramUpdate;


class TelegramController extends Controller{
    
    private $offset = 0;
    private $bot;

    public function init() {
        parent::init();
        $this->bot = \Yii::$app->bot;
    }

    public function actionIndex(){
        
        $updates = $this->$bot->getUpdates($this->getOffset() + 1);
        if(count($updates > 0)){
            echo "Новыйх сообщений:" . count($updates) . PHP_EOL;
            foreach ($updates as $update){
                $this->updateOffset($update);
                $this->processCommand($update->getMessage());
            }
        }else{
            echo "Новыйх сообщений нет" . PHP_EOL;
        }
        
        $this->sendUpdates();
    }
    
    private function getOffset(){
        $max = \common\models\tables\TelegramOffset::find()
                ->select('id')
                ->max('id');
        if($max > 0){
            $this->offset = $max;
        }
        return $this->offset;
    }
    
    private function updateOffset(\TelegramBot\Api\Types\Update $update){
        $model = new \common\models\tables\TelegramOffset([
            'id' => $update->getUpdateId(),
            'timestamp_offset' => date('Y-m-d H:i:s'),
        ]);
        
        $model->save();
    }
    
    private function processCommand($message){
        $params = explode(" ", $message->getText());
        $command = $params[0];
        
        switch ($command) {
            case '/sp_create':
                $sub = new \common\models\tables\TelegramSub([
                    'telegram_user_id' => $message->getForm()->getId(),
                    'project_id' => null,
                ]);
                $responce = "Вы подписались на создание проектов!";
                $this->bot->sendMessage($message->getForm()->getId(), $responce);
                break;
            case '/sp_update':
                
                $sub = new \common\models\tables\TelegramSub([
                    'telegram_user_id' => $message->getForm()->getId(),
                    'project_id' => $params[1],
                ]);
                $responce = "Вы подписались на обновление тасков для проекта {$message->getForm()->getId()}";
                $this->bot->sendMessage($message->getForm()->getId(), $responce);
                break;
        }
    }
    
    
    
    private function sendUpdates(){
        $lastId = TelegramUpdate::find()->max('id');
        $lastUpdate = TelegramUpdate::findOne($lastId);
        
        $newProjs = \common\models\Project::find()
                ->where(['>', 'id', $lastUpdate->last_proj_id])
                ->all();
        $newTasks = \common\models\task\Task::find()
                ->where(['>', 'id', $lastUpdate->last_task_id])
                ->all();
        
        $newLastProjId = 0;
        $newLastTaskId = 0;
        
        //Отправка сообщений с обновлением проектов.
        foreach ($newProjs as $projKey => $proj) {
            $responce = "Был создан проект: " . $proj->name;
            $subs = \common\models\tables\TelegramSub::find()
                    ->where(['project_id' => null])
                    ->all();
            
            foreach ($subs as $subKey => $sub){
                $this->bot->sendMessage($sub->telegram_user_id, $responce);
            }
            
            $newLastProjId = $proj->id;
        }
        
        //Отправка сообщений с обновлением тасков.
        foreach ($newTasks as $taskKey => $task) {
            $responce = "Проект был обновлен заданием: " . $task->name;
            $subs = \common\models\tables\TelegramSub::find()
                    ->where(['project_id' => $task->project_id])
                    ->all();
            
            foreach ($subs as $subKey => $sub){
                $this->bot->sendMessage($sub->telegram_user_id, $responce);
            }
            
            $newLastTaskId = $proj->id;
        }
        
        //Записываем новую информацию в табличку апдейтов
        $newUpdate = new TelegramUpdate([
            'last_proj_id' => $newLastProjId,
            'last_task_id' => $newLastTaskId,
        ]);
        $newUpdate->save();
    }
}
