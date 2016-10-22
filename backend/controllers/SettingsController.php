<?php
namespace backend\controllers;

use Yii;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\imagine\Image;
use backend\models\Profile;
use dektrium\user\controllers\SettingsController as BaseSettingsController;

class SettingsController extends BaseSettingsController
{
    /**
     * Выводит форму профиля.
     *
     * @return string|\yii\web\Response
     */
    public function actionProfile()
    {
        $model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());

        if ($model == null) {
            $model = \Yii::createObject(Profile::className());
            $model->link('user', \Yii::$app->user->identity);
        }

        $event = $this->getProfileEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
        if ($model->load(\Yii::$app->request->post())) {
            // сохраняем аватар
            $pifp = Profile::getPathToImages();
            $file = UploadedFile::getInstance($model, 'imageFile');
            $avatar_fn = $model->user_id.'.'.$file->extension;
            $avatar_ffp = $pifp.'/'.$avatar_fn;
            $thumb_fn = $model->user_id.'-thumb.'.$file->extension;
            $thumb_ffp = $pifp.'/'.$thumb_fn;
            // делаем запись в базу о нем
            $model->avatar_ffp = $avatar_ffp;
            $model->avatar_fn = $avatar_fn;
            $model->avatar_tffp = $thumb_ffp;
            $model->avatar_tfn = $thumb_fn;

            // сохраняем основное изображение
            $file->saveAs($avatar_ffp);
            // уменьшаем основное изображение
            Image::thumbnail($avatar_ffp, 800, 600)->save($avatar_ffp, ['quality' => 100]);
            // делаем thumbnail
            Image::thumbnail($avatar_ffp, 128, 128)->save($thumb_ffp, ['quality' => 100]);

            $model->avatar_size = filesize($avatar_ffp);

            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Your profile has been updated'));
                $this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
                return $this->refresh();
            }
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }
}