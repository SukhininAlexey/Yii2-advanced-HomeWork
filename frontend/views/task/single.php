<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\DetailView;
use yii\helpers\Url;
?>
<div class="post">
    <h2><?= Html::encode($model->name) ?></h2>
    <p><strong><?= \Yii::t('app', 'deadline') ?>: </strong><?= $model->date ?></p>
    <p><strong><?= \Yii::t('app', 'description') ?>: </strong><?= $model->description ?></p>
    <p><strong><?= \Yii::t('app', 'responsible') ?>: </strong><?= $user->username ?></p>
</div>



<div class="comments">

<?php foreach ($comments as $inst => $one): ?>
    
<table class="table table-hover" style="width:100%; max-width:1200px; margin-bottom:50px">
    <tr>
        <td class="text-center"><?= \Yii::t('app', 'owner') ?></td>
        <td class="text-center"><?= $one->user->login ?></td>
    </tr>
    <tr>
        <td class="text-center"><?= \Yii::t('app', 'date') ?></td>
        <td class="text-center"><?= $one->date ?></td>
    </tr>
    <tr>
        <td class="text-center"><?= \Yii::t('app', 'comment') ?></td>
        <td class="text-center"><?= $one->content ?></td>
    </tr>
    <tr>
        <td class="text-center"><?= \Yii::t('app', 'pictures') ?></td>
        <td class="text-center">
    <?php  foreach ($one['commentpics'] as $key => $pic):?>
                <a href="/Yii1_homework/Homework1/web/img/big/<?= $pic->file_name ?>" target="_blank">
                    <img src="/Yii1_homework/Homework1/web/img/small/<?= $pic->file_name ?>"> 
                </a>
    <?php endforeach;?>
        </td>
    </tr>
</table>
<?php endforeach;?>
</div>





<div class="comment-form">

<?php 

$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($comment, 'content')->textarea(['cols' => 40, 'rows' => 3]);;
echo $form->field($comment, 'images[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])->label(\Yii::t('app', 'images'));
echo \yii\helpers\Html::submitButton( \Yii::t('app', 'send'),['class' => 'btn btn-success']);

\yii\bootstrap\ActiveForm::end();

?>

</div>

