<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Payment;

/**
 * PaymentSearch represents the model behind the search form about `backend\models\Payment`.
 */
class PaymentSearch extends Payment
{
    // для сортировки
    public $pmName;
    public $currencyName;
    public $counteragentName;
    //public $facilityName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'pm_id', 'currency_id', 'ca_id', 'facility_id'], 'integer'],
            [['comment'], 'safe'],
            // для сортировки
            [['pmName', 'currencyName', 'counteragentName'], 'safe'],
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
        $query = Payment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['created_at' => SORT_DESC],
            'attributes' => [
                'id',
                'created_at',
                'pmName' => [
                    'asc' => ['payment_methods.name' => SORT_ASC],
                    'desc' => ['payment_methods.name' => SORT_DESC],
                ],
                'currencyName' => [
                    'asc' => ['currencies.name' => SORT_ASC],
                    'desc' => ['currencies.name' => SORT_DESC],
                ],
                'amount',
                'counteragentName' => [
                    'asc' => ['counteragents.name' => SORT_ASC],
                    'desc' => ['counteragents.name' => SORT_DESC],
                ],
//                'facilityName' => [
//                    'asc' => ['facilities.name' => SORT_ASC],
//                    'desc' => ['facilities.name' => SORT_DESC],
//                ],
                'comment',
            ]
        ]);

        $this->load($params);
        $query->joinWith(['pm', 'currency', 'counteragent']);
        //$query->leftJoin(['pm', 'currency', 'counteragent', 'facility']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'pm_id' => $this->pm_id,
            'currency_id' => $this->currency_id,
            'ca_id' => $this->ca_id,
            'facility_id' => $this->facility_id,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}