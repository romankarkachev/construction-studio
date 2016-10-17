<?php

use yii\db\Migration;

/**
 * Создается таблица Валюты.
 */
class m161006_185827_create_currencies_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('currencies', [
            'id' => $this->primaryKey(),
            'name' => $this->string(10)->notNull()->comment('Наименование'),
            'name_full' => $this->string(50)->notNull()->comment('Полное наименование'),
            'code' => $this->smallInteger(3)->comment('Код международный'),
        ], 'COMMENT "Валюты"');

        $this->insert('currencies', [
            'name' => 'руб.',
            'name_full' => 'Российский рубль',
            'code' => '643',
        ]);

        $this->insert('currencies', [
            'name' => 'USD',
            'name_full' => 'Американский доллар',
            'code' => '840',
        ]);

        $this->insert('currencies', [
            'name' => 'EUR',
            'name_full' => 'Евро',
            'code' => '978',
        ]);

        $this->insert('currencies', [
            'name' => 'PYG',
            'name_full' => 'Парагвайский гуарани́',
            'code' => '600',
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('currencies');
    }
}