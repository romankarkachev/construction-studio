<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Documents;

/**
 * DocumentsSearch represents the model behind the search form about `backend\models\Documents`.
 */
class DocumentsSearch extends Documents
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'facility_id', 'ca_id'], 'integer'],
            [['total_amount'], 'number'],
            [['comment'], 'safe'],
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
        $query = Documents::find();
        $query->select('*, (
            SELECT IFNULL(SUM(`documents_tp`.`amount`), 0) FROM `documents_tp` 
            WHERE `documents`.`id` = `documents_tp`.`doc_id`
        ) AS `tpAmount`, (
            SELECT COUNT(`documents_files`.`id`) FROM `documents_files`
            WHERE `documents`.`id` = `documents_files`.`doc_id`
        ) AS `filesCount`, (
            SELECT COUNT(`documents_tp`.`id`) FROM `documents_tp`
            WHERE `documents`.`id` = `documents_tp`.`doc_id`
        ) AS `rowsCount`');

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
            'facility_id' => $this->facility_id,
            'ca_id' => $this->ca_id,
            'total_amount' => $this->total_amount,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
