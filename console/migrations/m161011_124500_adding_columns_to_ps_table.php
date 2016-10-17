<?php

use yii\db\Migration;

/**
 * Выполняется добавление колонок Комментарий (внешний) и Наименование (полное) в таблицу номенклатуры ps.
 */
class m161011_124500_adding_columns_to_ps_table extends Migration
{
    public function up()
    {
        $this->addColumn('ps', 'name_full', $this->string(200)->notNull()->comment('Полное наименование').' AFTER `name`');
        $this->addColumn('ps', 'comment', $this->text()->comment('Примечание'));
    }

    public function down()
    {
        $this->dropColumn('ps', 'comment');
        $this->dropColumn('ps', 'name_full');
    }
}