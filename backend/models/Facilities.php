<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "facilities".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $name
 * @property string $name_external
 * @property string $identifier
 * @property integer $fs_id
 * @property integer $region_id
 * @property string $address
 * @property integer $customer_id
 * @property string $comment
 * @property string $comment_external
 * @property integer $documentsCount
 * @property integer $documentsAmount
 * @property integer $filesCount
 *
 * @property Documents[] $documents
 * @property Counteragents $customer
 * @property FacilitiesStates $state
 * @property Regions $region
 */
class Facilities extends \yii\db\ActiveRecord
{
    // для вложенного подзапроса
    public $documentsCount;
    public $documentsAmount;
    public $filesCount;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'facilities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'fs_id', 'region_id', 'customer_id'], 'integer'],
            [['name', 'name_external', 'fs_id', 'customer_id'], 'required'],
            [['comment', 'comment_external'], 'string'],
            [['name', 'name_external'], 'string', 'max' => 100],
            [['identifier'], 'string', 'max' => 8],
            [['identifier'], 'unique'],
            [['address'], 'string', 'max' => 200],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Counteragents::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['fs_id'], 'exist', 'skipOnError' => true, 'targetClass' => FacilitiesStates::className(), 'targetAttribute' => ['fs_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Regions::className(), 'targetAttribute' => ['region_id' => 'id']],
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
            'updated_at' => 'Дата и время обновления',
            'name' => 'Наименование',
            'name_external' => 'Наименование внешнее',
            'identifier' => 'Идентификатор',
            'fs_id' => 'Статус объекта',
            'region_id' => 'Населенный пункт',
            'address' => 'Адрес',
            'customer_id' => 'Заказчик',
            'comment' => 'Описание',
            'comment_external' => 'Комментарий (внешний)',
            // для вложенного подзапроса
            'documentsCount' => 'Документов',
            'documentsAmount' => 'Сумма документов',
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * Функция возвращает массив для использования в выпадающих списках форм.
     * @return array
     */
    public static function ArrayMap()
    {
        return ArrayHelper::map(Facilities::find()->orderBy('name')->all(), 'id', 'name');
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) $this->identifier = Yii::$app->security->generateRandomString(8);
            return true;
        }
        return false;
    }

    /**
     * Удаление связанных объектов перед удалением объекта.
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // deleteAll не вызывает beforeDelete
            // удаляем возможные документы
            $documents = Documents::find()->where(['facility_id' => $this->id])->all();
            foreach ($documents as $document) $document->delete();

            // удаляем возможные файлы
            $files = FacilitiesFiles::find()->where(['facility_id' => $this->id])->all();
            foreach ($files as $file) $file->delete();

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Documents::className(), ['facility_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Counteragents::className(), ['id' => 'customer_id']);
    }

    /**
     * Возвращает модель статуса объекта.
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(FacilitiesStates::className(), ['id' => 'fs_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Regions::className(), ['id' => 'region_id']);
    }

    /**
     * Функция проверяет, является ли файл изображением. Возвращает boolean.
     * @param $filepath
     * @return bool
     */
    public static function is_image($filepath) {
        $is = @getimagesize($filepath);
        if (!$is) return false;
        elseif (!in_array($is[2], array(1,2,3))) return false;
        else return true;
    }
}