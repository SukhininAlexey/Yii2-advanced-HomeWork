<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task`.
 */
class m180706_173105_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'description' => $this->text()->notNull(),
            'name' => $this->string()->notNull(),
            'date' => $this->decimal()->notNull(),
            'deadline' => $this->decimal()->notNull(),
            'status_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'leader_id' => $this->integer()->notNull(),
            'resolve_date' => $this->decimal(),
        ]);
        
        $this->addForeignKey('fk_task_status', 'task', 'status_id', 'task_status', 'id');
        $this->addForeignKey('fk_task_user', 'task', 'user_id', 'user', 'id');
        $this->addForeignKey('fk_task_leader', 'task', 'leader_id', 'user', 'id');
    }
    
    

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('task');
    }
}
