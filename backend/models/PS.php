<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "ps".
 *
 * @property integer $id
 * @property string $name
 * @property string $name_full
 * @property integer $pst_id
 * @property integer $bu_id
 * @property double $price
 * @property string $comment
 *
 * @property Units $baseUnit
 * @property PsTypes $type
 */
class PS extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ps';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_full', 'pst_id'], 'required'],
            [['pst_id', 'bu_id'], 'integer'],
            [['price'], 'number'],
            [['comment'], 'string'],
            [['name', 'name_full'], 'string', 'max' => 200],
            [['pst_id'], 'exist', 'skipOnError' => true, 'targetClass' => PSTypes::className(), 'targetAttribute' => ['pst_id' => 'id']],
            [['bu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Units::className(), 'targetAttribute' => ['bu_id' => 'id']],
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
            'pst_id' => 'Тип номенклатуры',
            'bu_id' => 'Базовая единица измерения',
            'price' => 'Обычная цена',
            'comment' => 'Примечание',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(PSTypes::className(), ['id' => 'pst_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseUnit()
    {
        return $this->hasOne(Units::className(), ['id' => 'bu_id']);
    }
}