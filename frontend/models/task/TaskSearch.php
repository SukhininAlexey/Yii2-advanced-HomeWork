<?php

namespace frontend\models\task;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\task\Task;

/**
 * TaskSearch represents the model behind the search form of `common\models\task\Task`.
 */
class TaskSearch extends Task
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'user_id', 'leader_id', 'project_id'], 'integer'],
            [['description', 'name'], 'safe'],
            [['date', 'deadline', 'resolve_date'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $projectId = null)
    {
        $query = Task::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        // Показываю только те проекты, которые имеют отношение к проектам, к которым подтянут юзер
        $hostUser = Yii::$app->user->id;
        
        // Ищем те задания, где юзер лидер, ответственный или где ID проекта соответствует тому, где мы есть
        $query->where([
            'or',
            ['leader_id' => $hostUser],
            ['user_id' => $hostUser],
            ['project_id' => $projectId],
        ]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        // Если при вызове метода задан id проекта, то фильтравать по проектам нелья, т.к. мы в интерфейсе проекта
        $whereProjId = $projectId ? $projectId : $this->project_id;
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'deadline' => $this->deadline,
            'status_id' => $this->status_id,
            'user_id' => $this->user_id,
            'leader_id' => $this->leader_id,
            'resolve_date' => $this->resolve_date,
            'project_id' => $whereProjId,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
