<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Counteragents;

/**
 * CounteragentsSearch represents the model behind the search form about `backend\models\Counteragents`.
 */
class CounteragentsSearch extends Counteragents
{
    public $searchName;
    public $searchOther;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'ct_id'], 'integer'],
            [['name', 'name_short', 'name_full', 'identifier', 'birthdate', 'phones', 'email', 'comment'], 'safe'],
            // для отбора
            [['searchName', 'searchOther'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['searchName'] = 'Наименование';
        $labels['searchOther'] = 'Контакты, примечание';

        return $labels;
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
        $query = Counteragents::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'ct_id' => $this->ct_id,
            'birthdate' => $this->birthdate,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_short', $this->name_short])
            ->andFilterWhere(['like', 'name_full', $this->name_full])
            ->andFilterWhere(['like', 'identifier', $this->identifier])
            ->orFilterWhere(['like', 'phones', $this->searchOther])
            ->orFilterWhere(['like', 'email', $this->searchOther])
            ->orFilterWhere(['like', 'comment', $this->searchOther]);

        return $dataProvider;
    }
}