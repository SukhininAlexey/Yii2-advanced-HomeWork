<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\task\Task */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

$lastUrls = Yii::$app->session['lastUrls'];
foreach ($lastUrls as $key => $value) {
    echo Html::a(ArrayHelper::getValue($value, 'task.name'), $value['url']) . '<br>';
}
?>

<div class="task-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= ( $permissions['update'] ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : null ) ?>
        <?= ( $permissions['resolve'] ? Html::a('Resolve', ['resolve', 'id' => $model->id], ['class' => 'btn btn-success']) : null ) ?>
        <?= ( $permissions['delete'] ? Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'name',
            'description:ntext',
            'date',
            'project_id',
            'deadline',
            'user_id',
            'leader_id',
            'status_id',
            'resolve_date',
        ],
    ]) ?>

</div>
