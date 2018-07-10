<?php

use yii\db\Migration;

/**
 * Handles adding status_id to table `project`.
 */
class m180710_004222_add_status_id_column_to_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('project', 'status_id', $this->integer());
        $this->addForeignKey('fk_project_status', 'project', 'status_id', 'project_status', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_project_status', 'project');
        $this->dropColumn('project', 'status_id');
    }
}
