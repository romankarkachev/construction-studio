<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ps_types".
 *
 * @property integer $id
 * @property string $name
 * @property string $name_plural_nominative_case
 * @property string $name_plural_genitive_case
 * @property string $name_plural_dative_case
 */
class PSTypes extends \yii\db\ActiveRecord
{
    const PS_TYPE_SERVICE = 1; // услуга
    const PS_TYPE_MATERIAL = 2; // материал

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ps_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'name_plural_nominative_case', 'name_plural_genitive_case', 'name_plural_dative_case'], 'string', 'max' => 30],
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
            'name_plural_nominative_case' => 'Мн. число, именит. падеж',
            'name_plural_genitive_case' => 'Мн. число, родит. падеж',
            'name_plural_dative_case' => 'Мн. число, дат. падеж',
        ];
    }

    /**
     * Функция возвращает массив для использования в выпадающих списках форм.
     * @return array
     */
    public static function ArrayMap()
    {
        return ArrayHelper::map(PSTypes::find()->orderBy('name')->all(), 'id', 'name');
    }
}