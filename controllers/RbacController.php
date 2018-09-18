<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
/**
 * Инициализатор RBAC выполняется в консоли php yii rbac/init
 */
class RbacController extends Controller {

    public function actionInit() {
        $auth = Yii::$app->authManager;

        $auth->removeAll(); //На всякий случай удаляем старые данные из БД...

        // Создадим роли админа и редактора новостей
        $admin = $auth->createRole('admin');
        $editor = $auth->createRole('editor');

        // запишем их в БД
        $auth->add($admin);
        $auth->add($editor);

        $viewPays = $auth->createPermission('viewPays');
        $viewPays->description = 'Просмотр платежей';

        $updateAccidents = $auth->createPermission('updateAccidents');
        $updateAccidents->description = 'Редактирование инцидентов';

        // Запишем эти разрешения в БД
        $auth->add($viewPays);
        $auth->add($updateAccidents);

        $auth->addChild($editor,$updateAccidents);

        $auth->addChild($admin, $editor);

        $auth->addChild($admin, $viewPays);

        // Назначаем роль admin пользователю с ID 1
        $auth->assign($admin, 1);

        // Назначаем роль editor пользователю с ID 2
        $auth->assign($editor, 2);
    }
}