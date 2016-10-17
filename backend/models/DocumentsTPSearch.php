<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DocumentsTP;

/**
 * DocumentsTPSearch represents the model behind the search form about `backend\models\DocumentsTP`.
 */
class DocumentsTPSearch extends DocumentsTP
{
    // для сортировки
    public $psName;
    public $unitName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'doc_id', 'ps_id', 'unit_id'], 'integer'],
            [['volume', 'price', 'amount'], 'number'],
            [['comment'], 'safe'],
            // для сортировки
            [['psName', 'unitName'], 'safe'],
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
        $query = DocumentsTP::find();
        $query->select('*, `documents_tp`.`id` AS `id`, `documents_tp`.`price` AS `price`, (
            SELECT COUNT(`documents_tp_files`.`id`) FROM `documents_tp_files`
            WHERE `documents_tp`.`id` = `documents_tp_files`.`row_id`
        ) AS `filesCount`');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['ps', 'unit']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'doc_id' => $this->doc_id,
            'ps_id' => $this->ps_id,
            'unit_id' => $this->unit_id,
            'volume' => $this->volume,
            'price' => $this->price,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}