<?php

namespace app\components;

use Yii;
use yii\helpers\Url;
use app\models\User;

class Init  extends \yii\base\Component  {

    public function init() {
        if (\Yii::$app->getUser()->isGuest &&
            \Yii::$app->getRequest()->url !== '/site/login'
        ) {
            \Yii::$app->getResponse()->redirect('/site/login');
        } else {
            User::checkUserSession();
        }

        parent::init();
    }
}