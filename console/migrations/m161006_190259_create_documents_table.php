<?php

use yii\db\Migration;

/**
 * Создается таблица Документы.
 */
class m161006_190259_create_documents_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('documents', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->comment('Дата и время создания'),
            'facility_id' => $this->integer()->notNull()->comment('Объект'),
            'ca_id' => $this->integer()->notNull()->comment('Контрагент'),
            'total_amount' => $this->double()->comment('Сумма договора'),
            'comment' => $this->text()->comment('Комментарий')
        ], 'COMMENT "Документы по выполнению работ"');

        $this->createIndex('facility_id', 'documents', 'facility_id');
        $this->createIndex('ca_id', 'documents', 'ca_id');

        $this->addForeignKey('fk_documents_facility_id', 'documents', 'facility_id', 'facilities', 'id');
        $this->addForeignKey('fk_documents_ca_id', 'documents', 'ca_id', 'counteragents', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_documents_ca_id', 'documents');
        $this->dropForeignKey('fk_documents_facility_id', 'documents');

        $this->dropIndex('ca_id', 'documents');
        $this->dropIndex('facility_id', 'documents');

        $this->dropTable('documents');
    }
}