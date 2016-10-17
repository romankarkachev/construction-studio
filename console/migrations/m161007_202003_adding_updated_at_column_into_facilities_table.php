<?php

use yii\db\Migration;

class m161007_202003_adding_updated_at_column_into_facilities_table extends Migration
{
    public function up()
    {
        $this->addColumn('facilities', 'updated_at', $this->integer()->comment('Дата и время обновления').' AFTER `created_at`');
    }

    public function down()
    {
        $this->dropColumn('facilities', 'updated_at');
    }
}