<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "documents_tp_files".
 *
 * @property integer $id
 * @property integer $uploaded_at
 * @property integer $row_id
 * @property integer $shared
 * @property string $ffp
 * @property string $fn
 * @property string $ofn
 * @property integer $size
 *
 * @property DocumentsTp $row
 */
class DocumentsTPFiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documents_tp_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['row_id', 'ffp', 'fn', 'ofn'], 'required'],
            [['uploaded_at', 'row_id', 'shared', 'size'], 'integer'],
            [['ffp', 'fn', 'ofn'], 'string', 'max' => 255],
            [['row_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentsTP::className(), 'targetAttribute' => ['row_id' => 'id']],
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
            'row_id' => 'Строка документа по выполнению работ',
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
     * После загрузки файла к строке табличной части документа необходимо обновить реквизит updated_at объекта.
     * @param bool $insert
     * @param array $changedAttributes
     * @return bool
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $this->row->doc->facility->updated_at = mktime();
        $this->row->doc->facility->save();
    }

    /**
     * Перед удалением информации о прикрепленном к строке табличной части файле, удалим его физически с диска.
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if (file_exists($this->ffp)) unlink($this->ffp);

            // обновим реквизит updated_at объекта
            $this->row->doc->facility->updated_at = mktime();
            $this->row->doc->facility->save();

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
        $filepath = Yii::getAlias('@uploads-dtpfs');
        if (!is_dir($filepath)) {
            if (!FileHelper::createDirectory($filepath)) return false;
        }

        return $filepath;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRow()
    {
        return $this->hasOne(DocumentsTP::className(), ['id' => 'row_id']);
    }
}