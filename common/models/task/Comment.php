<?php

namespace common\models\task;

use common\models\User;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property string $date
 * @property string $content
 * @property int $user_id
 * @property int $task_id
 *
 * @property CommentPics[] $commentpics
 * @property Tasks $task
 * @property Users $user
 */
class Comment extends \yii\db\ActiveRecord
{
    public $images = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['images'], 'file', 'extensions' => 'jpg, png', 'maxFiles' => 0],
            [['date'], 'required'],
            [['date'], 'safe'],
            [['content'], 'string'],
            [['user_id', 'task_id'], 'integer'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
    
    //Метод для получения комментария с написавшм его юзером
    public static function findAllWithUser($task_id){
        return static::find()
                ->with('user')
                ->with('commentpics')
                ->where(['task_id' => $task_id])
                //->asArray()
                ->all();
    }
    
    public function uploadImages(){
        $i = 0;
        foreach ($this->images as $key => $image) {
            $i++;
            $picObj = new \app\models\tables\CommentPic();
            $picObj->image = $image;
            $picObj->comment_id = $this->id;
            $picObj->uploadImage($i);
        }
    }

    
    

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'id'),
            'date' => \Yii::t('app', 'date'),
            'content' => \Yii::t('app', 'message'),
            'user_id' => \Yii::t('app', 'user id'),
            'task_id' => \Yii::t('app', 'task id'),
            'user.login' => \Yii::t('app', 'username'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentpics()
    {
        return $this->hasMany(CommentPic::className(), ['comment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
