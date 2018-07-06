<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\tables\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="task-form">

<?php 

echo \yii\helpers\Html::beginForm( Url::to(['task/index']), 'post');

echo \yii\helpers\Html::textInput('date', null, ['type' => 'month']);

echo \yii\helpers\Html::submitButton( \Yii::t('app', 'receive'), ['class' => 'btn btn-success']);

echo \yii\helpers\Html::endForm();


?>

</div>



<div class="task-index">

<table class="table table-hover" style="width:100%; max-width:1200px">
    
    <tr>
        <th class="text-center" style="width:150px"><?= \Yii::t('app', 'day') ?></th>
        <th class="text-left"><?= \Yii::t('app', 'task') ?></th>
        <th class="text-center" style="width:150px"><?= \Yii::t('app', 'quantity') ?></th>
    </tr>
    
<?php foreach ($calendar as $day => $content): ?>

    <tr>
        <td class="text-center"><?= $day ?></td>
        <td class="text-left">
    <?php  foreach ($calendar[$day] as $key => $task):?>
            <p>
                <a href="<?= Url::to(['task/single', 'taskId' => $calendar[$day][$key]->id]) ?>"><?= $calendar[$day][$key]->name ?></a>
                - <?= $calendar[$day][$key]->description ?>
            </p>
    <?php endforeach;?>
        </td>
        <td class="text-center"><?= count($calendar[$day]) ?></td>
    </tr>
    
    


<?php endforeach;?>
</table>
</div>
