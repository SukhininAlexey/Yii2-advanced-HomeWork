<?php

namespace common\models\tables;

use Yii;

/**
 * This is the model class for table "telegram_update".
 *
 * @property int $id
 * @property string $last_proj_id
 * @property string $last_task_id
 */
class TelegramUpdate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_update';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['last_proj_id', 'last_task_id'], 'required'],
            [['last_proj_id', 'last_task_id'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'last_proj_id' => 'Last Proj ID',
            'last_task_id' => 'Last Task ID',
        ];
    }
}
