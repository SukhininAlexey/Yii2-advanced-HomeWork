<?php

use yii\db\Migration;

/**
 * Handles the creation of table `telegram_update`.
 */
class m180714_015839_create_telegram_update_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('telegram_update', [
            'id' => $this->primaryKey(),
            'last_proj_id' => $this->decimal()->notNull(),
            'last_task_id' => $this->decimal()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('telegram_update');
    }
}
