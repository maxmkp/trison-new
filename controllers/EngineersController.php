<?php

namespace app\controllers;

use Yii;
use app\models\Engineers;
use app\models\EngineersSearch;
use app\models\Files;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\LoginForm;

/**
 * EngineersController implements the CRUD actions for Engineers model.
 */
class EngineersController extends Controller
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
     * Lists all Engineers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EngineersSearch();
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


//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
    }

    /**
     * Displays a single Engineers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Engineers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Engineers();

        if ($model->load(Yii::$app->request->post())) {

//            var_dump($model); die();

            if ($model->save()) {

                foreach ($_FILES as $type => $files_array) {
                    if ($files_array['error'][0] != 4) {
                        $filesInstances = UploadedFile::getInstancesByName($type);

                        foreach ($filesInstances as $file_up) {
                            $modelPics = new Files();
                            $modelPics->file = $file_up;
                            $filename = time() . Yii::$app->getSecurity()->generateRandomString(5);
                            $modelPics->file->saveAs('uploads/engineers/' . $type . '/' . $filename . '.' . $file_up->extension);
                            $modelPics->type = $type;
                            $modelPics->table_name = 'engineers';
                            $modelPics->name = $file_up->name;
                            $modelPics->link_id = $model->id;
                            $modelPics->url = '/uploads/engineers/' . $type . '/' . $filename . '.' . $file_up->extension;

                            $modelPics->save();

                        }
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'getParams' => Yii::$app->request->get(),
            ]);
        }
    }

    /**
     * Updates an existing Engineers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

//            var_dump($model); die();

            if ($model->save()) {

                foreach ($_FILES as $type => $files_array) {
                    if ($files_array['error'][0] != 4) {
                        $filesInstances = UploadedFile::getInstancesByName($type);

                        foreach ($filesInstances as $file_up) {

                            $modelPics = new Files();
                            $modelPics->file = $file_up;
                            $filename = time() . Yii::$app->getSecurity()->generateRandomString(5);
                            $modelPics->file->saveAs('uploads/engineers/' . $type . '/' . $filename . '.' . $file_up->extension);
                            $modelPics->type = $type;
                            $modelPics->table_name = 'engineers';
                            $modelPics->name = $file_up->name;
                            $modelPics->link_id = $model->id;
                            $modelPics->url = '/uploads/engineers/' . $type . '/' . $filename . '.' . $file_up->extension;

                            $modelPics->save();

                        }
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Engineers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);

        foreach ($model->files as $file) {
            if ($file->table=='accident') {
                if ((@unlink(Yii::getAlias('@webroot') . $file->url))||(!file_exists($_SERVER['DOCUMENT_ROOT'].$file->url))) {
                    $file->delete();
                }
            }
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Engineers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Engineers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Engineers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
