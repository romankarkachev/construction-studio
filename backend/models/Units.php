<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "units".
 *
 * @property integer $id
 * @property string $name
 * @property string $name_full
 *
 * @property DocumentsTp[] $documentsTps
 * @property Ps[] $ps
 */
class Units extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'units';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_full'], 'required'],
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
        ];
    }

    /**
     * Функция возвращает массив для использования в выпадающих списках форм.
     * @return array
     */
    public static function ArrayMap()
    {
        return ArrayHelper::map(Units::find()->orderBy('name')->all(), 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentsTps()
    {
        return $this->hasMany(DocumentsTP::className(), ['unit_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPs()
    {
        return $this->hasMany(PS::className(), ['bu_id' => 'id']);
    }
}