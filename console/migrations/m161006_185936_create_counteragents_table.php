<?php

use yii\db\Migration;

/**
 * Создается таблица Контрагенты.
 */
class m161006_185936_create_counteragents_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('counteragents', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull()->comment('Дата и время создания'),
            'name' => $this->string(200)->notNull()->comment('Наименование'),
            'name_short' => $this->string(200)->notNull()->comment('Наименование сокращенное'),
            'name_full' => $this->string(200)->notNull()->comment('Полное наименование'),
            'identifier' => $this->string(16)->comment('Идентификатор'),
            'ct_id' => $this->integer()->notNull()->comment('Тип контрагента'),
            'birthdate' => $this->date()->comment('Дата рождения'),
            'phones' => $this->string(100)->comment('Телефоны'),
            'email' => $this->string(255)->comment('E-mail'),
            'comment' => $this->text()->comment('Комментарий'),
        ], 'COMMENT "Контрагенты"');

        $this->createIndex('ct_id', 'counteragents', 'ct_id');

        $this->addForeignKey('fk_counteragents_ct_id', 'counteragents', 'ct_id', 'ca_types', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_counteragents_ct_id', 'counteragents');

        $this->dropIndex('ct_id', 'counteragents');

        $this->dropTable('counteragents');
    }
}