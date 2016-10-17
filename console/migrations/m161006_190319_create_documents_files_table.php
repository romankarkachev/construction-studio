<?php

use yii\db\Migration;

/**
 * Создается таблица Файлы к документам.
 */
class m161006_190319_create_documents_files_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('documents_files', [
            'id' => $this->primaryKey(),
            'uploaded_at' => $this->integer()->notNull()->comment('Дата и время загрузки'),
            'doc_id' => $this->integer()->notNull()->comment('Документ по выполнению работ'),
            'ffp' => $this->string(255)->notNull()->comment('Полный путь к файлу'),
            'fn' => $this->string(255)->notNull()->comment('Имя файла'),
            'ofn' => $this->string(255)->notNull()->comment('Оригинальное имя файла'),
            'size' => $this->integer()->comment('Размер файла')
        ], 'COMMENT "Файлы к документам по выполнению работ"');

        $this->createIndex('doc_id', 'documents_files', 'doc_id');

        $this->addForeignKey('fk_documents_files_', 'documents_files', 'doc_id', 'documents', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_documents_files_', 'documents_files');

        $this->dropIndex('doc_id', 'documents_files');

        $this->dropTable('documents_files');
    }
}