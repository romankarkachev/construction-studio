<?php

namespace backend\models;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use dektrium\user\models\Profile as BaseProfile;

/**
 * @property string $avatar_ffp
 * @property string $avatar_fn
 * @property string $avatar_tffp
 * @property string $avatar_tfn
 */
class Profile extends BaseProfile
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();

        unset($rules['gravatarEmailPattern']);
        unset($rules['gravatarEmailLength']);

        $rules[] = [['avatar_ffp', 'avatar_fn', 'avatar_tffp', 'avatar_tfn'], 'string', 'max' => 255];
        $rules[] = [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'gif,png,jpg'];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        $labels['imageFile'] = 'Аватар';

        return $labels;
    }

    /**
     * Функция определяет и возращает путь к папке для хранения изображений профилей.
     * Если каталог назначения не существует, система создает его.
     * Если создать не удалось, возвращается false. Иначе возвращается строка.
     *
     * @return mixed
     */
    static public function getPathToImages()
    {
        $filepath = Yii::getAlias('@uploads-pfs');
        if (!is_dir($filepath)) {
            if (!FileHelper::createDirectory($filepath)) return false;
        }

        return $filepath;
    }
}