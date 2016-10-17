<?php

use yii\db\Migration;

/**
 * Создается таблица Табличные части документов.
 */
class m161006_190311_create_documents_tp_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('documents_tp', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull()->comment('Дата и время добавления'),
            'doc_id' => $this->integer()->notNull()->comment('Документ по выполнению работ'),
            'ps_id' => $this->integer()->notNull()->comment('Номенклатура'),
            'unit_id' => $this->integer()->notNull()->comment('Единица измерения'),
            'volume' => $this->double()->notNull()->comment('Объем'),
            'price' => $this->double()->notNull()->comment('Цена'),
            'amount' => $this->double()->notNull()->comment('Сумма'),
            'comment' => $this->text()->comment('Примечание')
        ], 'COMMENT "Табличные части документов по выполнению работ"');

        $this->createIndex('doc_id', 'documents_tp', 'doc_id');
        $this->createIndex('ps_id', 'documents_tp', 'ps_id');
        $this->createIndex('unit_id', 'documents_tp', 'unit_id');

        $this->addForeignKey('fk_documents_tp_doc_id', 'documents_tp', 'doc_id', 'documents', 'id');
        $this->addForeignKey('fk_documents_tp_ps_id', 'documents_tp', 'ps_id', 'ps', 'id');
        $this->addForeignKey('fk_documents_tp_unit_id', 'documents_tp', 'unit_id', 'units', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_documents_tp_unit_id', 'documents_tp');
        $this->dropForeignKey('fk_documents_tp_ps_id', 'documents_tp');
        $this->dropForeignKey('fk_documents_tp_doc_id', 'documents_tp');

        $this->dropIndex('unit_id', 'documents_tp');
        $this->dropIndex('ps_id', 'documents_tp');
        $this->dropIndex('doc_id', 'documents_tp');

        $this->dropTable('documents_tp');
    }
}