<?php

use yii\db\Migration;

class m161006_151637_create_users_and_roles extends Migration
{
    public function up()
    {
        $role_adm = Yii::$app->authManager->createRole('root');
        $role_adm->description = 'Полные права';
        Yii::$app->authManager->add($role_adm);

        $role_man = Yii::$app->authManager->createRole('manager');
        $role_man->description = 'Менеджер';
        Yii::$app->authManager->add($role_man);

        $role_cli = Yii::$app->authManager->createRole('customer');
        $role_cli->description = 'Заказчик';
        Yii::$app->authManager->add($role_cli);

        $user_adm = new \dektrium\user\models\User();
        $user_adm->username = 'root';
        $user_adm->email = 'root@gmail.com';
        $user_adm->password = '1Qazxsw2';
        $user_adm->confirmed_at = mktime();
        $user_adm->save();
        Yii::$app->authManager->assign($role_adm, $user_adm->id);

        $user_man = new \dektrium\user\models\User();
        $user_man->username = 'manager';
        $user_man->email = 'manager@gmail.com';
        $user_man->password = '123456';
        $user_man->confirmed_at = mktime();
        $user_man->save();
        Yii::$app->authManager->assign($role_man, $user_man->id);
    }

    public function down()
    {
        $role_adm = Yii::$app->authManager->getRole('root');
        Yii::$app->authManager->remove($role_adm);

        $role_adm = Yii::$app->authManager->getRole('manager');
        Yii::$app->authManager->remove($role_adm);

        $role_adm = Yii::$app->authManager->getRole('customer');
        Yii::$app->authManager->remove($role_adm);

        // связи само удаляет, проверено
        // теперь пользователи
        $user = \dektrium\user\models\User::findOne(['username' => 'root'])->delete();
        $user = \dektrium\user\models\User::findOne(['username' => 'manager'])->delete();
    }
}