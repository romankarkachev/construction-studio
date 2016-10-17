<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ca_types".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Counteragents[] $counteragents
 */
class CATypes extends \yii\db\ActiveRecord
{
    const CA_TYPE_CUSTOMER = 1;
    const CA_TYPE_INTERMEDIARY = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ca_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
        ];
    }

    /**
     * Функция возвращает массив для использования в выпадающих списках форм.
     * @return array
     */
    public static function ArrayMap()
    {
        return ArrayHelper::map(CATypes::find()->orderBy('name')->all(), 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCounteragents()
    {
        return $this->hasMany(Counteragents::className(), ['ct_id' => 'id']);
    }
}