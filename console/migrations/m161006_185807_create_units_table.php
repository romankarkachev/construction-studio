<?php

use yii\db\Migration;

/**
 * Создание таблицы Единицы измерения.
 */
class m161006_185807_create_units_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('units', [
            'id' => $this->primaryKey(),
            'name' => $this->string(10)->notNull()->comment('Наименование'),
            'name_full' => $this->string(50)->notNull()->comment('Полное наименование'),
        ], 'COMMENT "Единицы измерения"');

        $this->insert('units', ['name' => 'м', 'name_full' => 'Метр']);
        $this->insert('units', ['name' => 'м²', 'name_full' => 'Метр квадратный']);
        $this->insert('units', ['name' => 'м³', 'name_full' => 'Метр кубический']);
        $this->insert('units', ['name' => 'пог. м', 'name_full' => 'Метр погонный']);
        $this->insert('units', ['name' => 'кг', 'name_full' => 'Килограмм']);
        $this->insert('units', ['name' => 'т', 'name_full' => 'Тонна']);
        $this->insert('units', ['name' => 'шт.', 'name_full' => 'Штука']);
        $this->insert('units', ['name' => 'уп.', 'name_full' => 'Упаковка']);
        $this->insert('units', ['name' => 'л', 'name_full' => 'Литр']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('units');
    }
}