<?php

namespace frontend\modules\rest\controllers;

use common\models\task\Task;
use common\models\task\TaskSearch;



class RestController extends \yii\rest\ActiveController {
    
    public $modelClass = Task::class;
    
    public function behaviors(){
        
        $behaviors = parent::behaviors();
        $behaviors['authentificator'] = [
            'class' => \yii\filters\auth\HttpBasicAuth::class,
            'auth' => function($username, $password){
                $user = \common\models\User::findByUsername($username);
                if($user !== null && $user->validatePassword($password)){
                    return $user;
                }
                return null;
            }
        ];
        return $behaviors;
    }
}
