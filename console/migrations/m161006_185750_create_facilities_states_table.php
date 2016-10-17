<?php

use yii\db\Migration;

/**
 * Создание таблицы Статусы объектов (недвижимости).
 */
class m161006_185750_create_facilities_states_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('facilities_states', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->comment('Наименование'),
        ], 'COMMENT "Статусы строительных объектов"');

        $this->insert('facilities_states', ['name' => 'Переговоры']);
        $this->insert('facilities_states', ['name' => 'Выполняется работа']);
        $this->insert('facilities_states', ['name' => 'Работы завершены']);
        $this->insert('facilities_states', ['name' => 'Отказ заказчика']);
        $this->insert('facilities_states', ['name' => 'Отказ']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('facilities_states');
    }
}