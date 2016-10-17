<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "currencies".
 *
 * @property integer $id
 * @property string $name
 * @property string $name_full
 * @property integer $code
 */
class Currencies extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currencies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_full'], 'required'],
            [['code'], 'integer'],
            [['name'], 'string', 'max' => 10],
            [['name_full'], 'string', 'max' => 50],
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
            'name_full' => 'Полное наименование',
            'code' => 'Код',
        ];
    }

    /**
     * Функция возвращает массив для использования в выпадающих списках форм.
     * @return array
     */
    public static function ArrayMap()
    {
        return ArrayHelper::map(Currencies::find()->orderBy('name')->all(), 'id', 'name');
    }

    /**
     * Функция возвращает массив для использования в выпадающих списках форм.
     * @return array
     */
    public static function ArrayMapForSelect2()
    {
        $result = [];

        foreach (Currencies::find()->select('id, name, name_full')->orderBy('name')->all() as $currency) {
            $result['arrayMap'][$currency->id] = $currency->name;
            $result['extraData'][$currency->id] = ['data-nf' => $currency->name_full];
        }

        return $result;
    }
}