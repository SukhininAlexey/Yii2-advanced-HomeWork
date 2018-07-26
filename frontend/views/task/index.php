<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\task\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute'=>'name',
                'format' => 'raw',
                'value'=>function ($data) {
                    return Html::a("$data->name", yii\helpers\Url::to(['task/view', 'id' => $data->id]), ['data-pjax' => 0]);
                },
            ],
            'description:ntext',
            'date',
            ['label' => 'Project', 'attribute' => 'user_id', 'value' => 'project.name'],
            /*
            [
                'label' => 'Project', 
                'attribute' => 'projectName',
                'format' => 'raw',
                'value'=>function ($data) {
                    return Html::a("$data->projectName", yii\helpers\Url::to(['project/view', 'id' => $data->project_id]), ['data-pjax' => 0]);
                },
            ],*/
            'deadline',
            ['label' => 'Owner', 'attribute' => 'userName'],
            ['label' => 'Leader', 'attribute' => 'leaderName'],
            ['label' => 'Status', 'attribute' => 'statusName'],
            //'resolve_date',
            

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
