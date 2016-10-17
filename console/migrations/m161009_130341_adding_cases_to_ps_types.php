<?php

use yii\db\Migration;

/**
 * Добавляются колонки с падежами для склонения типов номенклатуры.
 */
class m161009_130341_adding_cases_to_ps_types extends Migration
{
    public function up()
    {
        $this->addColumn('ps_types', 'name_plural_nominative_case', $this->string(30)->comment('Во множественном числе'));
        $this->addColumn('ps_types', 'name_plural_genitive_case', $this->string(30)->comment('Родительный падеж (кого? чего?)'));
        $this->addColumn('ps_types', 'name_plural_dative_case', $this->string(30)->comment('Дательный падеж (кому? чему?)'));
    }

    public function down()
    {
        $this->dropColumn('ps_types', 'name_plural_nominative_case');
        $this->dropColumn('ps_types', 'name_plural_genitive_case');
        $this->dropColumn('ps_types', 'name_plural_dative_case');
    }
}