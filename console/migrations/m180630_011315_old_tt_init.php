<?php

use yii\db\Migration;

/**
 * Class m180630_011315_old_tt_init
 */
class m180630_011315_old_tt_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        //Tasks table ajustments
        $this->createTable('tasks', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'date' => $this->dateTime()->notNull(),
            'description' => $this->text(),
            'user_id' => $this->integer()
        ]);
        
        $this->addForeignKey('fk_tasks_users', 'tasks', 'user_id', 'user', 'id');
        
        $this->addColumn('tasks', 'created_at', $this->dateTime());
        $this->addColumn('tasks', 'updated_at', $this->dateTime());
        
        
        //Comments table ajustments
        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'date' => $this->dateTime()->notNull(),
            'content' => $this->text(),
            'user_id' => $this->integer(),
            'task_id' => $this->integer()
        ]);
        
        $this->addForeignKey('fk_comments_users', 'comments', 'user_id', 'user', 'id');
        $this->addForeignKey('fk_comments_tasks', 'comments', 'task_id', 'tasks', 'id');
        
        
        // Comment_pics table ajustments
        $this->createTable('comment_pics', [
            'id' => $this->primaryKey(),
            'file_name' => $this->text(),
            'view_name' => $this->text(),
            'comment_id' => $this->integer()
        ]);
        
        $this->addForeignKey('fk_comments_pics', 'comment_pics', 'comment_id', 'comments', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tasks');
        $this->dropTable('comments');
        $this->dropTable('comment_pics');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180630_011315_old_tt_init cannot be reverted.\n";

        return false;
    }
    */
}
