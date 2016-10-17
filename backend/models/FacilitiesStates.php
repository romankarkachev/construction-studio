<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "facilities_states".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Facilities[] $facilities
 */
class FacilitiesStates extends \yii\db\ActiveRecord
{
    const STATE_NEGOTIATIONS = 1; // Переговоры
    const STATE_PROCESS = 2; // Выполняется работа
    const STATE_DONE = 3; // Работы завершены
    const STATE_CUSTOMER_REJECTED = 4; // Отказ заказчика
    const STATE_REJECT = 5; // Отказ

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'facilities_states';
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
        return ArrayHelper::map(FacilitiesStates::find()->orderBy('name')->all(), 'id', 'name');
    }

    /**
     * Возвращает цвет, применяемый для визуального обозначения статуса.
     * @param $id
     * @return string
     */
    public static function getStatesColors($id)
    {
        $result = [];
        switch ($id) {
            case self::STATE_NEGOTIATIONS:
                // переговоры зеленым
                $result['panels'] = ' hgreen';
                $result['labels'] = 'success';
                break;
            case self::STATE_PROCESS:
                // выполняемые синим
                $result['panels'] = ' hblue';
                $result['labels'] = 'info';
                break;
            case self::STATE_REJECT:
            case self::STATE_CUSTOMER_REJECTED:
                // отказные красным
                $result['panels'] = ' hred';
                $result['labels'] = 'danger';
                break;
            default:
                // все остальные (в т.ч. выполненные) - без отделки
                $result['panels'] = '';
                $result['labels'] = 'default';
                $result['headers'] = ' class="font-trans"';
                break;
        }

        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacilities()
    {
        return $this->hasMany(Facilities::className(), ['fs_id' => 'id']);
    }
}