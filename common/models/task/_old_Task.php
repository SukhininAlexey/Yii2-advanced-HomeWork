<?php

namespace common\models\task;

use common\models\User;
use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $name
 * @property string $date
 * @property string $description
 * @property int $user_id
 *
 * @property Users $user
 */
class Task extends \yii\db\ActiveRecord
{
    const SCENARIO_ADMIN = 'admin';
    const SCENARIO_USER = 'user';

    public function scenarios() {
        return [
            self::SCENARIO_ADMIN => ['name', 'date', 'description', 'user_id'],
            self::SCENARIO_USER => ['description']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'date'], 'required'],
            [['date'], 'safe'],
            [['description'], 'string'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
    
     

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    
    
  /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'date' => 'Дедлайн',
            'description' => 'Описание',
            'user_id' => 'ID пользователя',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public static function getUserTasksByMonth($user_id, $year, $month){
        return $tasksResult = \app\models\tables\Task::find()
                ->where(['user_id' => $user_id])
                ->andWhere(['YEAR(`date`)' => $year])
                ->andWhere(['MONTH(`date`)' => $month])
                ->all();
    }
}
