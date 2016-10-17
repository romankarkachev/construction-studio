<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "documents_tp".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $doc_id
 * @property integer $ps_id
 * @property integer $unit_id
 * @property double $volume
 * @property double $price
 * @property double $amount
 * @property string $comment
 * @property string $comment_external
 *
 * @property Documents $doc
 * @property Ps $ps
 * @property Units $unit
 * @property DocumentsTpFiles[] $documentsTpFiles
 * @property DocumentsTpFiles[] $sharedFiles
 */
class DocumentsTP extends \yii\db\ActiveRecord
{
    // для вложенного подзапроса
    public $filesCount;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documents_tp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['doc_id', 'ps_id', 'volume', 'price', 'amount'], 'required'],
            [['created_at', 'doc_id', 'ps_id', 'unit_id'], 'integer'],
            [['volume', 'price', 'amount'], 'number'],
            [['comment', 'comment_external'], 'string'],
            [['doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Documents::className(), 'targetAttribute' => ['doc_id' => 'id']],
            [['ps_id'], 'exist', 'skipOnError' => true, 'targetClass' => PS::className(), 'targetAttribute' => ['ps_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Units::className(), 'targetAttribute' => ['unit_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата и время добавления',
            'doc_id' => 'Документ по выполнению работ',
            'ps_id' => 'Номенклатура',
            'unit_id' => 'Единица измерения',
            'volume' => 'Объем',
            'price' => 'Цена',
            'amount' => 'Сумма',
            'comment' => 'Примечание',
            'comment_external' => 'Примечание (внешнее)',
            'comments' => 'Примечания',
            // для сортировки
            'psName' => 'Номенклатура',
            'unitName' => 'Ед. изм.',
            // для вложенного подзапроса
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
     * После создания (изменения) строки табличной части документа необходимо обновить реквизит updated_at у объекта.
     * @param bool $insert
     * @param array $changedAttributes
     * @return bool
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $this->doc->facility->updated_at = mktime();
        $this->doc->facility->save();
    }

    /**
     * Перед удалением строки табличной части удалим возможные прикрепленные к ней файлы.
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // deleteAll не вызывает beforeDelete
            $dtpfs = DocumentsTPFiles::find()->where(['row_id' => $this->id])->all();
            foreach ($dtpfs as $dtpf) $dtpf->delete();

            // обновим реквизит updated_at объекта
            $this->doc->facility->updated_at = mktime();
            $this->doc->facility->save();

            return true;
        }
        else return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoc()
    {
        return $this->hasOne(Documents::className(), ['id' => 'doc_id']);
    }

    /**
     * Возвращает дату и время создания документа, к которому относится строка.
     * @return int
     */
    public function getDocCreatedAt()
    {
        return $this->doc->created_at;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPs()
    {
        return $this->hasOne(PS::className(), ['id' => 'ps_id']);
    }

    /**
     * Возвращает наименование номенклатуры.
     * @return string
     */
    public function getPsName()
    {
        return $this->ps->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Units::className(), ['id' => 'unit_id']);
    }

    /**
     * Возвращает наименование единицы измерения.
     * @return string
     */
    public function getUnitName()
    {
        return $this->unit->name;
    }

    /**
     * Возвращаются все файлы, без учета уровня доступа!
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentsTpFiles()
    {
        return $this->hasMany(DocumentsTPFiles::className(), ['row_id' => 'id']);
    }

    /**
     * Функция делает выборку только тех файлов, которые пользователь сделал общедоступными.
     * @return \yii\db\ActiveQuery
     */
    public function getSharedFiles()
    {
        return $this->hasMany(DocumentsTPFiles::className(), ['row_id' => 'id'])->where(['shared' => true]);
    }
}