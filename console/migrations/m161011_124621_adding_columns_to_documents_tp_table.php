<?php

use yii\db\Migration;

/**
 * Выполняется добавление колонки Примечание (внешнее) в таблицу строк табличных частей документов documents_tp.
 */
class m161011_124621_adding_columns_to_documents_tp_table extends Migration
{
    public function up()
    {
        $this->addColumn('documents_tp', 'comment_external', $this->text()->comment('Примечание (внешнее)').' AFTER `comment`');
    }

    public function down()
    {
        $this->dropColumn('documents_tp', 'comment_external');
    }

}