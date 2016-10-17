<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "facilities_files".
 *
 * @property integer $id
 * @property integer $uploaded_at
 * @property integer $facility_id
 * @property integer $shared
 * @property string $ffp
 * @property string $fn
 * @property string $ofn
 * @property integer $size
 *
 * @property Facilities $facility
 */
class FacilitiesFiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'facilities_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['facility_id', 'ffp', 'fn', 'ofn'], 'required'],
            [['uploaded_at', 'facility_id', 'shared', 'size'], 'integer'],
            [['ffp', 'fn', 'ofn'], 'string', 'max' => 255],
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
            'uploaded_at' => 'Загружен',
            'facility_id' => 'Строительный объект',
            'shared' => 'Общий доступ',
            'ffp' => 'Полный путь к файлу',
            'fn' => 'Имя файла',
            'ofn' => 'Оригинальное имя файла',
            'size' => 'Размер файла',
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['uploaded_at'],
                ],
            ],
        ];
    }

    /**
     * После загрузки файла к объекту необходимо обновить реквизит updated_at у объекта.
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $this->facility->updated_at = mktime();
        $this->facility->save();
    }

    /**
     * Перед удалением информации о прикрепленном к объекту файле, удалим его физически с диска.
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if (file_exists($this->ffp)) unlink($this->ffp);

            // обновим реквизит updated_at у объекта
            $this->facility->updated_at = mktime();
            $this->facility->save();

            return true;
        }
        else return false;
    }

    /**
     * Возвращает путь к папке, в которую необходимо поместить загружаемые пользователем файлы.
     * Если папка не существует, она будет создана. Если создание провалится, будет возвращено false.
     * @return bool|string
     */
    public static function getUploadsFilepath()
    {
        $filepath = Yii::getAlias('@uploads-ffs');
        if (!is_dir($filepath)) {
            if (!FileHelper::createDirectory($filepath)) return false;
        }

        return $filepath;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacility()
    {
        return $this->hasOne(Facilities::className(), ['id' => 'facility_id']);
    }
}