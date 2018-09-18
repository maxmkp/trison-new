<?php

namespace app\controllers;


use Yii;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;

/**
 * WorksController implements the CRUD actions for Works model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionChangeShowCols()
    {
        $post_fields=Yii::$app->request->post();
        $userID=Yii::$app->user->getId();
        $user = $this->findModel($userID);

        if ($user->shown_cols == 'null') $array_shown_cols = '';
        else $array_shown_cols = $user->shown_cols;

        $shown_cols = json_decode($array_shown_cols, true);
        $shown_cols[$post_fields['name']] = $post_fields['fields'];
        $user->shown_cols = json_encode($shown_cols);
        $_SESSION['user']['shown_cols'] = $user->shown_cols;
//        print_r($user->shown_cols);die();
        $user->save();
    }

    /**
     * Finds the Works model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
