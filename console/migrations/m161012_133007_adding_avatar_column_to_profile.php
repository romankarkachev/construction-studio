<?php

use yii\db\Migration;

/**
 * Добавляется колонка Аватар в профиль пользователя.
 */
class m161012_133007_adding_avatar_column_to_profile extends Migration
{
    public function up()
    {
        $this->dropColumn('profile', 'gravatar_email');
        $this->dropColumn('profile', 'gravatar_id');

        $this->addColumn('profile', 'avatar_ffp', $this->string(255)->comment('Полный путь к файлу аватара'));
        $this->addColumn('profile', 'avatar_fn', $this->string(255)->comment('Имя файла аватара'));
        $this->addColumn('profile', 'avatar_tffp', $this->string(255)->comment('Полный путь к файлу миниатюры аватара'));
        $this->addColumn('profile', 'avatar_tfn', $this->string(255)->comment('Имя файла миниатюры аватара'));
        $this->addColumn('profile', 'avatar_size', $this->integer()->comment('Размер файла аватара'));
    }

    public function down()
    {
        $this->addColumn('profile', 'gravatar_email', $this->string(255).' AFTER `public_email`');
        $this->addColumn('profile', 'gravatar_id', $this->string(32).' AFTER `gravatar_email`');

        $this->dropColumn('profile', 'avatar_size');
        $this->dropColumn('profile', 'avatar_tfn');
        $this->dropColumn('profile', 'avatar_tffp');
        $this->dropColumn('profile', 'avatar_fn');
        $this->dropColumn('profile', 'avatar_ffp');
    }
}