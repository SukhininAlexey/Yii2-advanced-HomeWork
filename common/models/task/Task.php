<?php

namespace common\models\task;

use Yii;
use \common\models\User;
use \common\models\Project;


/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $description
 * @property string $name
 * @property string $date
 * @property string $deadline
 * @property int $status_id
 * @property int $user_id
 * @property int $leader_id
 * @property string $resolve_date
 * @property int $project_id
 *
 * @property User $leader
 * @property Project $project
 * @property TaskStatus $status
 * @property User $user
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'name', 'date', 'deadline', 'status_id', 'user_id', 'leader_id', 'project_id'], 'required'],
            [['description'], 'string'],
            [['date', 'deadline', 'resolve_date'], 'number'],
            [['status_id', 'user_id', 'leader_id', 'project_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['leader_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['leader_id' => 'id']],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::className(), 'targetAttribute' => ['project_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaskStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
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
            'description' => 'Description',
            'name' => 'Name',
            'date' => 'Date',
            'deadline' => 'Deadline',
            'status_id' => 'Status ID',
            'user_id' => 'User ID',
            'leader_id' => 'Leader ID',
            'resolve_date' => 'Resolve Date',
            'project_id' => 'Project ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeader()
    {
        return $this->hasOne(User::className(), ['id' => 'leader_id']);
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
    public function getStatus()
    {
        return $this->hasOne(TaskStatus::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
