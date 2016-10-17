<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "counteragents".
 *
 * @property integer $id
 * @property integer $created_at
 * @property string $name
 * @property string $name_short
 * @property string $name_full
 * @property string $identifier
 * @property integer $ct_id
 * @property string $birthdate
 * @property string $phones
 * @property string $email
 * @property string $comment
 *
 * @property CaTypes $type
 * @property Documents[] $documents
 * @property Facilities[] $facilities
 */
class Counteragents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counteragents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'name_short', 'name_full', 'ct_id'], 'required'],
            [['created_at', 'ct_id'], 'integer'],
            [['birthdate'], 'safe'],
            [['comment'], 'string'],
            [['name', 'name_short', 'name_full'], 'string', 'max' => 200],
            [['identifier'], 'string', 'max' => 16],
            [['identifier'], 'unique'],
            [['phones'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 255],
            [['ct_id'], 'exist', 'skipOnError' => true, 'targetClass' => CATypes::className(), 'targetAttribute' => ['ct_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата и время создания',
            'name' => 'Наименование',
            'name_short' => 'Наименование сокращенное',
            'name_full' => 'Полное наименование',
            'identifier' => 'Идентификатор',
            'ct_id' => 'Тип контрагента',
            'birthdate' => 'Дата рождения',
            'phones' => 'Телефоны',
            'email' => 'E-mail',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) $this->identifier = Yii::$app->security->generateRandomString(16);
            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(CATypes::className(), ['id' => 'ct_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Documents::className(), ['ca_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacilities()
    {
        return $this->hasMany(Facilities::className(), ['customer_id' => 'id']);
    }
}