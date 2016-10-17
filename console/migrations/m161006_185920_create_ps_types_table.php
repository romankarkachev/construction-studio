<?php

use yii\db\Migration;

/**
 * Создается таблица Типы номенклатуры.
 */
class m161006_185920_create_ps_types_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('ps_types', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->comment('Наименование'),
        ], 'COMMENT "Типы номенклатуры"');

        $this->insert('ps_types', ['name' => 'Услуга']);
        $this->insert('ps_types', ['name' => 'Материал']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('ps_types');
    }
}