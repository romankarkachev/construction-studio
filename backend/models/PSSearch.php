<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PS;

/**
 * PSSearch represents the model behind the search form about `backend\models\PS`.
 */
class PSSearch extends PS
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pst_id', 'bu_id'], 'integer'],
            [['name', 'name_full', 'comment'], 'safe'],
            [['price'], 'number'],
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
        $query = PS::find();

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
            'pst_id' => $this->pst_id,
            'bu_id' => $this->bu_id,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_full', $this->name_full])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
