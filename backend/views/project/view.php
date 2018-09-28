<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Project */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= ( $permissions['update'] ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : null ) ?>
        <?= ( $permissions['update'] ? Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) : null ) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'description:ntext',
            'name',
            'date',
            'estimated_end_date',
            'leader_id',
        ],
    ]) ?>
    
    <h1><?= Html::encode("{$model->name} team:") ?></h1>
    <p>
        <?= ( $permissions['addUser'] ? Html::a(
                    'Add user', 
                    ['member/create', 'project_id' => $model->id], 
                    ['class' => 'btn btn-success']
                ) : null ) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $userDataProvider,
        'filterModel' => $userSearchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
            //'status',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ( $permissions['addUser'] ? '{delete}' : '' ),
                'buttons' => [
                    'delete' => function ($url, $user, $key) use ($model) {
                        return Html::a('', 
                                ['member/delete'], 
                                [
                                    'data-method' => 'POST',
                                    'data-params' => [
                                        'user_id' => $user->id,
                                        'project_id' => $model->id,
                                    ],
                                    'data' => ['confirm' => 'Are you sure you want to delete this item?'],
                                    'class' => 'glyphicon glyphicon-trash'
                                ]);
                    }
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    
    
    <h1><?= Html::encode("{$model->name} tasks:") ?></h1>
    <p>
        <?= ( $permissions['addTask'] ? Html::a(
                    'Add task', 
                    ['task/create', 'project_id' => $model->id], 
                    ['class' => 'btn btn-success']
                ) : null ) ?>
    </p>
    
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $actionDataProvider,
        'filterModel' => $actionSearchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute'=>'name',
                'format' => 'raw',
                'value'=>function ($task) {
                    return Html::a("$task->name", yii\helpers\Url::to(['task/view', 'id' => $task->id]), ['data-pjax' => 0]);
                },
            ],
            'description:ntext',
            'date',
            'deadline',
            'user_id',
            'leader_id',
            'status_id',
            //'resolve_date',

            /*
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'delete' => function ($url, $task, $key) use ($model) {
                        return Html::a('', 
                                ['task/delete'], 
                                [
                                    'data-method' => 'POST',
                                    'data-params' => [
                                        'task_id' => $task->id,
                                        'project_id' => $model->id,
                                    ],
                                    'class' => 'glyphicon glyphicon-trash'
                                ]);
                    },
                    'update' => function ($url, $task, $key) use ($model) {
                        return Html::a('', 
                                ['task/update', 'id' => $task->id], 
                                ['class' => 'glyphicon glyphicon-pencil']
                            );
                    },
                    'view' => function ($url, $task, $key) use ($model) {
                        return Html::a('', 
                                ['task/view', 'id' => $task->id], 
                                ['class' => 'glyphicon glyphicon-eye-open']
                            );
                    }
                ],
            ],
            */
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
