<?php

use yii\db\Migration;

/**
 * Создается таблица Регионы.
 */
class m161006_185909_create_regions_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('regions', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->comment('Родительский элемент'),
            'name' => $this->string(100)->notNull()->comment('Наименование')
        ], 'COMMENT "Регионы и населенные пункты"');

        $this->createIndex('parent_id', 'regions', 'parent_id');

        $this->addForeignKey('fk_regions_parent_id', 'regions', 'parent_id', 'regions', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_regions_parent_id', 'regions');

        $this->dropIndex('parent_id', 'regions');

        $this->dropTable('regions');
    }
}