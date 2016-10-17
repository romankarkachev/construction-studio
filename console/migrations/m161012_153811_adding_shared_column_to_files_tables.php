<?php

use yii\db\Migration;

/**
 * Выполняется добавление колонки Общий доступ в таблицы с файлами к элементам.
 */
class m161012_153811_adding_shared_column_to_files_tables extends Migration
{
    public function up()
    {
        // к строительным объектам
        $this->addColumn('facilities_files', 'shared', $this->smallInteger(1)->notNull()->defaultValue(0)->comment('Признак общего доступа к файлу').' AFTER `facility_id`');

        // к документам
        $this->addColumn('documents_files', 'shared', $this->smallInteger(1)->notNull()->defaultValue(0)->comment('Признак общего доступа к файлу').' AFTER `doc_id`');

        // к строкам табличных частей документов
        $this->addColumn('documents_tp_files', 'shared', $this->smallInteger(1)->notNull()->defaultValue(0)->comment('Признак общего доступа к файлу').' AFTER `row_id`');
    }

    public function down()
    {
        $this->dropColumn('documents_tp_files', 'shared');
        $this->dropColumn('documents_files', 'shared');
        $this->dropColumn('facilities_files', 'shared');
    }
}