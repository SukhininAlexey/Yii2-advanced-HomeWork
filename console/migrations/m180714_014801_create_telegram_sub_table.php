<?php

use yii\db\Migration;

/**
 * Handles the creation of table `telegram_subs`.
 */
class m180714_014801_create_telegram_sub_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('telegram_sub', [
            'id' => $this->primaryKey(),
            'telegram_user_id' => $this->decimal()->notNull(),
            'project_id' => $this->decimal(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('telegram_sub');
    }
}
