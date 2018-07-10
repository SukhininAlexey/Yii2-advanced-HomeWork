<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $description
 * @property string $name
 * @property string $date
 * @property string $estimated_end_date
 * @property int $leader_id
 * @property int $status_id
 *
 * @property User $leader
 * @property ProjectStatus $status
 * @property Task[] $tasks
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'name', 'date', 'leader_id'], 'required'],
            [['description'], 'string'],
            [['date', 'estimated_end_date'], 'number'],
            [['leader_id', 'status_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['leader_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['leader_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProjectStatus::className(), 'targetAttribute' => ['status_id' => 'id']],
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
            'estimated_end_date' => 'Estimated End Date',
            'leader_id' => 'Leader ID',
            'status_id' => 'Status ID',
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
    public function getStatus()
    {
        return $this->hasOne(ProjectStatus::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['project_id' => 'id']);
    }
}
