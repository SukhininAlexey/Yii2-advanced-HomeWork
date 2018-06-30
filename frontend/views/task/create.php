<?php

use yii\widgets\ActiveForm;
/** @var \app\models\tables\Task $model */

echo '<h1>Создание нового задания</h1>';

$form = ActiveForm::begin([
    'id' => 'create_task',
    'options' => [
        'class' => 'form-vertical',
    ]
]);


echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'date')->textInput(['type' => 'date']);
echo $form->field($model, 'description')->textArea();
echo $form->field($model, 'user_id')->dropDownList(app\models\tables\User::getIdLoginArray());
echo \yii\helpers\Html::submitButton("Создать",['class' => 'btn btn-success']);

ActiveForm::end();

