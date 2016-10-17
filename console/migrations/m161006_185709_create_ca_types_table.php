<?php

use yii\db\Migration;

/**
 * Создание таблицы Типы контрагентов.
 */
class m161006_185709_create_ca_types_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('ca_types', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->comment('Наименование'),
        ], 'COMMENT "Типы контрагентов"');

        $this->insert('ca_types', ['name' => 'Заказчик']);
        $this->insert('ca_types', ['name' => 'Посредник']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('ca_types');
    }
}