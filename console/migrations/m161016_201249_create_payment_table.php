<?php

use yii\db\Migration;

/**
 * Создается таблица Оплата.
 */
class m161016_201249_create_payment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('payment', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull()->comment('Дата и время создания'),
            'pm_id' => $this->integer()->notNull()->comment('Форма оплаты'),
            'currency_id' => $this->integer()->notNull()->comment('Валюта'),
            'ca_id' => $this->integer()->notNull()->comment('Контрагент'),
            'facility_id' => $this->integer()->comment('Объект'),
            'rate' => $this->decimal(4)->comment('Курс валюты'),
            'amount' => $this->double()->notNull()->comment('Сумма'),
            'amount_n' => $this->double()->notNull()->comment('Сумма в рублях'),
            'comment' => $this->text()->comment('Комментарий'),
        ], 'COMMENT "Оплата"');

        $this->createIndex('pm_id', 'payment', 'pm_id');
        $this->createIndex('currency_id', 'payment', 'currency_id');
        $this->createIndex('ca_id', 'payment', 'ca_id');
        $this->createIndex('facility_id', 'payment', 'facility_id');

        $this->addForeignKey('fk_payment_pm_id', 'payment', 'pm_id', 'payment_methods', 'id');
        $this->addForeignKey('fk_payment_currency_id', 'payment', 'currency_id', 'currencies', 'id');
        $this->addForeignKey('fk_payment_ca_id', 'payment', 'ca_id', 'counteragents', 'id');
        $this->addForeignKey('fk_payment_facility_id', 'payment', 'facility_id', 'facilities', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_payment_facility_id', 'payment');
        $this->dropForeignKey('fk_payment_ca_id', 'payment');
        $this->dropForeignKey('fk_payment_currency_id', 'payment');
        $this->dropForeignKey('fk_payment_pm_id', 'payment');

        $this->dropIndex('facility_id', 'payment');
        $this->dropIndex('ca_id', 'payment');
        $this->dropIndex('currency_id', 'payment');
        $this->dropIndex('pm_id', 'payment');

        $this->dropTable('payment');
    }
}