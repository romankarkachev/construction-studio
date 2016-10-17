<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Facilities;

/**
 * FacilitiesSearch represents the model behind the search form about `backend\models\Facilities`.
 */
class FacilitiesSearch extends Facilities
{
    public $searchCommon;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'fs_id', 'region_id', 'customer_id'], 'integer'],
            [['name', 'name_external', 'identifier', 'address', 'comment', 'comment_external'], 'safe'],
            // для отбора
            [['searchCommon'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['searchCommon'] = 'Поиск';

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
        $query = Facilities::find();
        // количество файлов в документах:
        //(
        //SELECT COUNT(`documents_files`.`id`) FROM `documents_files`
        //    INNER JOIN `documents` ON `documents`.`id` = `documents_files`.`doc_id`
        //    WHERE `facilities`.`id` = `documents`.`facility_id`
        //) AS `filesCount`
        $query->select('*, (
            SELECT COUNT(`documents`.`id`) FROM `documents`
            WHERE `facilities`.`id` = `documents`.`facility_id`
        ) AS `documentsCount`, (
            SELECT IFNULL(SUM(`documents_tp`.`amount`), 0) FROM `documents_tp`
            INNER JOIN `documents` ON `documents`.`id` = `documents_tp`.`doc_id` 
            WHERE `facilities`.`id` = `documents`.`facility_id`
        ) AS `documentsAmount`, (
            SELECT COUNT(`facilities_files`.`id`) FROM `facilities_files`
            WHERE `facilities`.`id` = `facilities_files`.`facility_id`
        ) AS `filesCount`');

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
            'fs_id' => $this->fs_id,
            'region_id' => $this->region_id,
            'customer_id' => $this->customer_id,
        ]);

        $query->orFilterWhere(['like', 'name', $this->searchCommon])
            ->orFilterWhere(['like', 'name_external', $this->searchCommon])
            ->andFilterWhere(['like', 'identifier', $this->identifier])
            ->orFilterWhere(['like', 'address', $this->searchCommon])
            ->orFilterWhere(['like', 'comment', $this->searchCommon])
            ->orFilterWhere(['like', 'comment_external', $this->searchCommon]);

        return $dataProvider;
    }
}