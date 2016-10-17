<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "payment".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $pm_id
 * @property integer $currency_id
 * @property integer $ca_id
 * @property integer $facility_id
 * @property string $rate
 * @property double $amount
 * @property double $amount_n
 * @property string $comment
 *
 * @property Facilities $facility
 * @property Counteragents $counteragent
 * @property Currencies $currency
 * @property PaymentMethods $pm
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pm_id', 'currency_id', 'ca_id', 'amount'], 'required'],
            [['created_at', 'pm_id', 'currency_id', 'ca_id', 'facility_id'], 'integer'],
            [['rate', 'amount', 'amount_n'], 'number'],
            [['comment'], 'string'],
            [['facility_id'], 'exist', 'skipOnError' => true, 'targetClass' => Facilities::className(), 'targetAttribute' => ['facility_id' => 'id']],
            [['ca_id'], 'exist', 'skipOnError' => true, 'targetClass' => Counteragents::className(), 'targetAttribute' => ['ca_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currencies::className(), 'targetAttribute' => ['currency_id' => 'id']],
            [['pm_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethods::className(), 'targetAttribute' => ['pm_id' => 'id']],
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
            'pm_id' => 'Форма оплаты',
            'currency_id' => 'Валюта',
            'ca_id' => 'Контрагент',
            'facility_id' => 'Объект',
            'rate' => 'Курс валюты',
            'amount' => 'Сумма',
            'amount_n' => 'Сумма в рублях',
            'comment' => 'Комментарий',
            // для сортировки
            'pmName' => 'Форма оплаты',
            'currencyName' => 'Валюта',
            'counteragentName' => 'Контрагент',
            'facilityName' => 'Объект',
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
     * Пока не появится необходимость или хотя бы понимание, как применять валюту,
     * сумма в национальной валюте всегда приравнивается к сумме в валюте.
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->rate = 1; // курс 1 к 1
            $this->amount_n = $this->amount;
            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPm()
    {
        return $this->hasOne(PaymentMethods::className(), ['id' => 'pm_id']);
    }

    /**
     * Наименование формы оплаты.
     * @return string
     */
    public function getPmName()
    {
        return $this->pm->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currencies::className(), ['id' => 'currency_id']);
    }

    /**
     * Наименование валюты.
     * @return string
     */
    public function getCurrencyName()
    {
        return $this->currency->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCounteragent()
    {
        return $this->hasOne(Counteragents::className(), ['id' => 'ca_id']);
    }

    /**
     * Наименование контрагента.
     * @return string
     */
    public function getCounteragentName()
    {
        return $this->counteragent->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacility()
    {
        return $this->hasOne(Facilities::className(), ['id' => 'facility_id']);
    }

    /**
     * Наименование объекта строительства.
     * @return string
     */
    public function getFacilityName()
    {
        return $this->facility->name;
    }
}