<?php

namespace common\models\tables;

use Yii;

/**
 * This is the model class for table "telegram_sub".
 *
 * @property int $id
 * @property string $telegram_user_id
 * @property string $project_id
 */
class TelegramSub extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram_sub';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['telegram_user_id'], 'required'],
            [['telegram_user_id', 'project_id'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'telegram_user_id' => 'Telegram User ID',
            'project_id' => 'Project ID',
        ];
    }
}
