<?php

use yii\db\Migration;

/**
 * Handles the creation of table `team`.
 */
class m180725_063442_create_member_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'project_id' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk_member_user', 'member', 'user_id', 'user', 'id');
        $this->addForeignKey('fk_member_project', 'member', 'project_id', 'project', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('member');
    }
}
