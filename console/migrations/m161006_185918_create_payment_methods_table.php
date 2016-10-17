<?php

use yii\db\Migration;

/**
 * Создается таблица Способы оплаты.
 */
class m161006_185918_create_payment_methods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('payment_methods', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->comment('Наименование'),
        ], 'COMMENT "Способы оплаты"');

        $this->insert('payment_methods', ['name' => 'Наличные']);
        $this->insert('payment_methods', ['name' => 'Банковская карта']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('payment_methods');
    }
}