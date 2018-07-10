<?php

use yii\db\Migration;

/**
 * Handles the creation of table `project`.
 */
class m180709_230652_create_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('project', [
            'id' => $this->primaryKey(),
            'description' => $this->text()->notNull(),
            'name' => $this->string()->notNull(),
            'date' => $this->decimal()->notNull(),
            'estimated_end_date' => $this->decimal(),
            'leader_id' => $this->integer()->notNull(),
        ]);
        
        $this->addForeignKey('fk_project_leader', 'project', 'leader_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('project');
    }
}
