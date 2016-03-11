<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Slot;

/**
 * SlotSearch represents the model behind the search form about `app\models\Slot`.
 */
class SlotSearch extends Slot
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slotID', 'dayID', 'agendaID'], 'integer'],
            [['content', 'type', 'date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
    public function search($params)
    {
        $query = Slot::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'slotID' => $this->slotID,
            'dayID' => $this->dayID,
            'agendaID' => $this->agendaID,
        ]);

        $query->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'date', $this->date]);

        return $dataProvider;
    }
}
