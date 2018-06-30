<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\tables\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) // \Yii::$app->user->can('createTask') ? Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) : NULL ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'date',
            'description:ntext',
            'user_id',
            

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url,$model) {
                        return \Yii::$app->user->can('createTask') ? Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>', 
                        $url) : NULL;
                    },
                    'delete' => function ($url, $model, $key) {
                        return \Yii::$app->user->can('deleteTask') ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => 'Are you sure you want to delete?',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : NULL;
                    },
                ],

            ],
        ],
    ]); ?>
</div>
