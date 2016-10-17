<?php

use yii\db\Migration;

/**
 * Колонка unit_id в таблице ps становится необязательной для заполнения.
 * Для услуг указание единицы измерения не является обязательным.
 */
class m161009_111336_make_unit_not_required extends Migration
{
    public function up()
    {
        $this->alterColumn('documents_tp', 'unit_id', $this->integer()->comment('Единица измерения'));
    }

    public function down()
    {
        $this->alterColumn('documents_tp', 'unit_id', $this->integer()->notNull()->comment('Единица измерения'));
    }
}