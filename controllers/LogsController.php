<?php

namespace app\controllers;

use Yii;
use app\models\Logs;
use app\models\LogsSearch;
use app\models\Files;
use app\models\LoginForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LogsController implements the CRUD actions for Logs model.
 */
class LogsController extends Controller
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

    /**
     * Lists all Logs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (!Yii::$app->user->isGuest) {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'user' => $_SESSION['user'],
                'shown_cols' => $_SESSION['user']['shown_cols'],
            ]);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Logs model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Logs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Logs();

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
//            echo '<pre>'; print_r($model); echo '</pre>';
//            die();//
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Logs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Logs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Logs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Logs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Logs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreateMessage()
    {
        $userID = $_SESSION['user']['id'];
        $post_fields = Yii::$app->request->post();

        $result = Logs::createMessage($userID, $post_fields['text'], ((!empty($post_fields['accident_id'])) ? $post_fields['accident_id'] : false), ((!empty($post_fields['work_id'])) ? $post_fields['work_id'] : false), 'MESSAGE', ((!empty($post_fields['recipients'])) ? implode(",",$post_fields['recipients']) : false), ((!empty($post_fields['recipients_eng'])) ? implode(",",$post_fields['recipients_eng']) : false), $_FILES);

        if ($result===true) {

            $searchModel = new LogsSearch();
            $filter_fields = array();

            if (!empty($post_fields['work_id'])) $filter_fields["LogsSearch"]["work_id"] = $post_fields['work_id'];
            elseif (!empty($post_fields['accident_id'])) $filter_fields["LogsSearch"]["accident_id"] = $post_fields['accident_id'];

            $dataProvider = $searchModel->search($filter_fields);
            $models = $dataProvider->getModels();

//            print_r($filter_fields);
//            die();

            return $this->renderPartial('logs_ajax', [
                'pagination'=>$dataProvider->pagination,
                'models' => $models,
                'user_id' => $userID
            ]);

        } else return $result;

//        $file_changes = Files::saveFiles('logs', $result_id, $_FILES);

//        print_r($result_id);
//        print_r($_FILES);
//        die();

//обработка всего входящего
    }

    public function actionViewMessages()
    {
        $searchModel = new LogsSearch();
        $searchParams = Yii::$app->request->post();
        $userID = $_SESSION['user']['id'];

        $dataProvider = $searchModel->search($searchParams);
        if (!empty($searchParams['page'])) $dataProvider->pagination->page = $searchParams['page'];

        $models = $dataProvider->getModels();

//        print_r($dataProvider->pagination);

        return $this->renderPartial('logs_ajax', [
            'pagination'=>$dataProvider->pagination,
            'models' => $models,
            'user_id' => $userID
        ]);
    }
}
