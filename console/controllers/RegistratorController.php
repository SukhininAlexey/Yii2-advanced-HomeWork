<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace console\controllers;

use yii\console\Controller;
use yii\console\ExitCode;

class RegistratorController extends Controller
{

    public function actionInit()
    {
        $am = \Yii::$app->authManager;

        $admin = $am->createRole('admin'); // раздает права остальным 
        $leader = $am->createRole('leader'); // управляет командами
        $user = $am->createRole('user'); // является частью команды
        
        $am->add($admin);
        $am->add($leader);
        $am->add($user);
        
        $operationChangeRole = (new \yii\rbac\DbManager())->createPermission('changeRole');
        
        $operationCheckTeam = $am->createPermission('checkTeam');
        $operationCreateTeam = $am->createPermission('createTeam');
        $operationEditTeam = $am->createPermission('editTeam');
        $operationDeleteTeam = $am->createPermission('deleteTeam');
        
        $operationAddToTeam = $am->createPermission('addToTeam');
        $operationRemoveFromTeam = $am->createPermission('removeFromTeam');
        
        $operationCheckTask = $am->createPermission('checkTask');
        $operationResolveTask = $am->createPermission('resolveTask');
        $operationCreateTask = $am->createPermission('createTask');
        $operationEditTask = $am->createPermission('editTask');
        $operationDeleteTask = $am->createPermission('deleteTask');
        
        $am->add($operationChangeRole);
        $am->add($operationCheckTeam);
        $am->add($operationCreateTeam);
        $am->add($operationEditTeam);
        $am->add($operationDeleteTeam);
        $am->add($operationAddToTeam);
        $am->add($operationRemoveFromTeam);
        $am->add($operationCheckTask);
        $am->add($operationResolveTask);
        $am->add($operationCreateTask);
        $am->add($operationEditTask);
        $am->add($operationDeleteTask);
        
        $am->addChild($admin, $operationChangeRole);
        
        $am->addChild($leader, $operationCheckTeam);
        $am->addChild($leader, $operationCreateTeam);
        $am->addChild($leader, $operationEditTeam);
        $am->addChild($leader, $operationDeleteTeam);
        $am->addChild($leader, $operationAddToTeam);
        $am->addChild($leader, $operationRemoveFromTeam);
        $am->addChild($leader, $operationCheckTask);
        $am->addChild($leader, $operationResolveTask);
        $am->addChild($leader, $operationCreateTask);
        $am->addChild($leader, $operationEditTask);
        $am->addChild($leader, $operationDeleteTask);
        
        $am->addChild($user, $operationCheckTeam);
        $am->addChild($user, $operationCheckTask);
        $am->addChild($user, $operationResolveTask);
        
        $this->actionAssign();
        
        return ExitCode::OK;
    }
    
    public function actionAssign(){
        $am = \Yii::$app->authManager;
        $admin = $am->getRole('admin');
        $leader = $am->getRole('leader');
        $user = $am->getRole('user');
        
        $am->assign($admin, 1);
        $am->assign($leader, 2);
        $am->assign($user, 3);
        
    }
}
