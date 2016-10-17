<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "documents".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $facility_id
 * @property integer $ca_id
 * @property double $total_amount
 * @property string $comment
 *
 * @property Counteragents $customer
 * @property Facilities $facility
 * @property DocumentsFiles[] $documentsFiles
 * @property DocumentsFiles[] $sharedFiles
 * @property DocumentsTP[] $documentsTP
 */
class Documents extends \yii\db\ActiveRecord
{
    // для вложенного подзапроса
    public $tpAmount; // сумма табличной части
    public $rowsCount; // количество строк табличной части
    public $filesCount; // количество файлов, прикрепленных к документу

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'facility_id', 'ca_id'], 'integer'],
            [['facility_id', 'ca_id'], 'required'],
            [['total_amount'], 'number'],
            [['comment'], 'string'],
            [['ca_id'], 'exist', 'skipOnError' => true, 'targetClass' => Counteragents::className(), 'targetAttribute' => ['ca_id' => 'id']],
            [['facility_id'], 'exist', 'skipOnError' => true, 'targetClass' => Facilities::className(), 'targetAttribute' => ['facility_id' => 'id']],
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
            'facility_id' => 'Объект',
            'ca_id' => 'Контрагент',
            'total_amount' => 'Сумма договора',
            'comment' => 'Комментарий',
            'facilityName' => 'Объект',
            'customerName' => 'Контрагент',
            // для вложенного подзапроса
            'tpAmount' => 'Сумма',
            'rowsCount' => 'Строк',
            'filesCount' => 'Файлов',
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
     * После создания (изменения) документа необходимо обновить реквизит updated_at объекта.
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $this->facility->updated_at = mktime();
        $this->facility->save();
    }

    /**
     * Удаление связанных объектов перед удалением документа.
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // deleteAll не вызывает beforeDelete
            // удаляем строки табличных частей
            $dtps = DocumentsTP::find()->where(['doc_id' => $this->id])->all();
            foreach ($dtps as $dtp) $dtp->delete();

            // удаляем возможные прикрепленные к документу файлы
            $dfs = DocumentsFiles::find()->where(['doc_id' => $this->id])->all();
            foreach ($dfs as $df) $df->delete();

            // обновим реквизит updated_at объекта
            $this->facility->updated_at = mktime();
            $this->facility->save();

            return true;
        }
        else return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacility()
    {
        return $this->hasOne(Facilities::className(), ['id' => 'facility_id']);
    }

    /**
     * Возвращает наименование объекта.
     * @return string
     */
    public function getFacilityName()
    {
        return $this->facility->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Counteragents::className(), ['id' => 'ca_id']);
    }

    /**
     * Возвращает сокращенное наименование контрагента.
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customer->name_short;
    }

    /**
     * Возвращаются все файлы, без учета уровня доступа!
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentsFiles()
    {
        return $this->hasMany(DocumentsFiles::className(), ['doc_id' => 'id']);
    }

    /**
     * Функция делает выборку только тех файлов, которые пользователь сделал общедоступными.
     * @return \yii\db\ActiveQuery
     */
    public function getSharedFiles()
    {
        return $this->hasMany(DocumentsTPFiles::className(), ['doc_id' => 'id'])->where(['shared' => true]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentsTP()
    {
        return $this->hasMany(DocumentsTP::className(), ['doc_id' => 'id']);
    }
}