<?php

use yii\db\Migration;

/**
 * Создается таблица Номенклатура.
 */
class m161006_185928_create_ps_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('ps', [
            'id' => $this->primaryKey(),
            'name' => $this->string(200)->notNull()->comment('Наименование'),
            'pst_id' => $this->integer()->notNull()->comment('Тип номенклатуры'),
            'bu_id' => $this->integer()->comment('Базовая единица измерения'),
            'price' => $this->double()->comment('Обычная цена'),
        ], 'COMMENT "Номенклатура"');

        $this->createIndex('bu_id', 'ps', 'bu_id');
        $this->createIndex('pst_id', 'ps', 'pst_id');

        $this->addForeignKey('fk_ps_bu_id', 'ps', 'bu_id', 'units', 'id');
        $this->addForeignKey('fk_ps_pst_id', 'ps', 'pst_id', 'ps_types', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_ps_pst_id', 'ps');
        $this->dropForeignKey('fk_ps_bu_id', 'ps');

        $this->dropIndex('pst_id', 'ps');
        $this->dropIndex('bu_id', 'ps');

        $this->dropTable('ps');
    }
}