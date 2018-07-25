<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "member".
 *
 * @property int $id
 * @property int $user_id
 * @property int $project_id
 *
 * @property Project $project
 * @property User $user
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'project_id'], 'required'],
            [['user_id', 'project_id'], 'integer'],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'project_id' => 'Project ID',
        ];
    }
    
    public static function getUserProjects($user_id){
        return $projsArr = static::find()
                ->asArray()
                ->where(['user_id' => $user_id])
                ->select('project_id')
                ->all();
    }
    
    public static function getProjPossibleUsersArr($project_id){
        
        $projUserArr = static::getProjUserArr($project_id);
        $projUserIds = ArrayHelper::getColumn($projUserArr, 'user_id');
        
        return $possibleUserArr = User::find()
                ->asArray()
                ->where(['NOT IN', 'id', $projUserIds])
                ->select(['id', 'username'])
                ->all();
    }
    
    public static function getProjCurrentUserArr($project_id){
        
        $projUserArr = static::getProjUserArr($project_id);
        $projUserIds = ArrayHelper::getColumn($projUserArr, 'user_id');
                
        return $possibleUserArr = User::find()
                ->asArray()
                ->where(['id' => $projUserIds])
                ->select(['id', 'username'])
                ->all();
    }
    
    public static function getProjUserArr($project_id){
        
        return $currentUserArr = static::find()
                ->asArray()
                ->where(['project_id' => $project_id])
                ->select('user_id')
                ->all();
    }


        /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
