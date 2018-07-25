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
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
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
        <?= Html::a(
                    'Add user', 
                    ['member/create', 'project_id' => $model->id], 
                    ['class' => 'btn btn-success']
                ) ?>
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
                'template' => '{delete}',
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
        <?= Html::a(
                    'Add task', 
                    ['task/create', 'project_id' => $model->id], 
                    ['class' => 'btn btn-success']
                ) ?>
    </p>
    
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $actionDataProvider,
        'filterModel' => $actionSearchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            'description:ntext',
            'date',
            'deadline',
            'user_id',
            'leader_id',
            'status_id',
            //'resolve_date',
            

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
                                ['task/update', 'task_id' => $task->id, 'project_id' => $model->id], 
                                ['class' => 'glyphicon glyphicon-pencil']
                            );
                    }
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
