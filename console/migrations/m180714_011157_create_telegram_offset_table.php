<?php

use yii\db\Migration;

/**
 * Handles the creation of table `telegram_offset`.
 */
class m180714_011157_create_telegram_offset_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('telegram_offset', [
            'id' => $this->primaryKey(),
            'timestemp_offset' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('telegram_offset');
    }
}
