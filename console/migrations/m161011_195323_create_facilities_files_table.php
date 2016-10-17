<?php

use yii\db\Migration;

/**
 * Создается таблица Файлы к строительным объектам.
 */
class m161011_195323_create_facilities_files_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('facilities_files', [
            'id' => $this->primaryKey(),
            'uploaded_at' => $this->integer()->notNull()->comment('Дата и время загрузки'),
            'facility_id' => $this->integer()->notNull()->comment('Строительный объект'),
            'ffp' => $this->string(255)->notNull()->comment('Полный путь к файлу'),
            'fn' => $this->string(255)->notNull()->comment('Имя файла'),
            'ofn' => $this->string(255)->notNull()->comment('Оригинальное имя файла'),
            'size' => $this->integer()->comment('Размер файла')
        ], 'COMMENT "Файлы к строительным объектам"');

        $this->createIndex('facility_id', 'facilities_files', 'facility_id');

        $this->addForeignKey('fk_facilities_files_facility_id', 'facilities_files', 'facility_id', 'facilities', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_facilities_facility_id', 'facilities_files');

        $this->dropIndex('facility_id', 'facilities_files');

        $this->dropTable('facilities_files');
    }
}