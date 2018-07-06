<?php
namespace common\tests;

class TaskTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        $task = new \common\models\task\Task([
            'name' => 'testName',
            'date' => 777,
            'description' => 'taskDescription',
            'deadline' => 888,
            'status_id' => 1,
            'user_id' =>  1,
            'leader_id' => 1,
            'resolve_date' => 999,
        ]);
        $task->save();
    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
        // Запрашиваем ранее созданный таск
        $task = \common\models\task\Task::findOne(\Yii::$app->db->lastInsertID);
        
        // Проверяем все поля на совпадение
        $this->assertEquals('testName', $task->name);
        $this->assertEquals('taskDescription', $task->description);
        $this->assertEquals(888, $task->deadline);
        $this->assertEquals(777, $task->date);
        $this->assertEquals(1, $task->status_id);
        $this->assertEquals(1, $task->user_id);
        $this->assertEquals(1, $task->leader_id);
        $this->assertEquals(999, $task->resolve_date);
    }
}