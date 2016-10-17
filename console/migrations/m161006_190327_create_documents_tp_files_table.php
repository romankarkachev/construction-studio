<?php

use yii\db\Migration;

/**
 * Создается таблица Файлы к строкам табличной части документов.
 */
class m161006_190327_create_documents_tp_files_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('documents_tp_files', [
            'id' => $this->primaryKey(),
            'uploaded_at' => $this->integer()->notNull()->comment('Дата и время загрузки'),
            'row_id' => $this->integer()->notNull()->comment('Строка документа по выполнению работ'),
            'ffp' => $this->string(255)->notNull()->comment('Полный путь к файлу'),
            'fn' => $this->string(255)->notNull()->comment('Имя файла'),
            'ofn' => $this->string(255)->notNull()->comment('Оригинальное имя файла'),
            'size' => $this->integer()->comment('Размер файла')
        ], 'COMMENT "Файлы к строкам табличной части документов по выполнению работ"');

        $this->createIndex('row_id', 'documents_tp_files', 'row_id');

        $this->addForeignKey('fk_documents_tp_files_row_id', 'documents_tp_files', 'row_id', 'documents_tp', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_documents_tp_files_row_id', 'documents_tp_files');

        $this->dropIndex('row_id', 'documents_tp_files');

        $this->dropTable('documents_tp_files');
    }
}