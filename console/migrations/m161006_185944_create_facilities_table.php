<?php

use yii\db\Migration;

/**
 * Создается таблица Объекты.
 */
class m161006_185944_create_facilities_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('facilities', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->comment('Дата и время создания'),
            'name' => $this->string(100)->notNull()->comment('Наименование'),
            'name_external' => $this->string(100)->notNull()->comment('Наименование внешнее'),
            'identifier' => $this->string(8)->comment('Идентификатор'),
            'fs_id' => $this->integer()->notNull()->comment('Статус объекта'),
            'region_id' => $this->integer()->comment('Населенный пункт'),
            'address' => $this->string(200)->comment('Адрес'),
            'customer_id' => $this->integer()->notNull()->comment('Заказчик'),
            'comment' => $this->text()->comment('Комментарий'),
            'comment_external' => $this->text()->comment('Комментарий внешний'),
        ], 'COMMENT "Строительные объекты"');

        $this->createIndex('fs_id', 'facilities', 'fs_id');
        $this->createIndex('region_id', 'facilities', 'region_id');
        $this->createIndex('customer_id', 'facilities', 'customer_id');

        $this->addForeignKey('fk_facilities_fs_id', 'facilities', 'fs_id', 'facilities_states', 'id');
        $this->addForeignKey('fk_facilities_region_id', 'facilities', 'region_id', 'regions', 'id');
        $this->addForeignKey('fk_facilities_customer_id', 'facilities', 'customer_id', 'counteragents', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_facilities_customer_id', 'facilities');
        $this->dropForeignKey('fk_facilities_region_id', 'facilities');
        $this->dropForeignKey('fk_facilities_fs_id', 'facilities');

        $this->dropIndex('customer_id', 'facilities');
        $this->dropIndex('region_id', 'facilities');
        $this->dropIndex('fs_id', 'facilities');

        $this->dropTable('facilities');
    }
}