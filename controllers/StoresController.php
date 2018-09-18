<?php

namespace app\controllers;

use app\models\Files;
use Yii;
use app\models\Stores;
use app\models\StoresSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\LoginForm;

/**
 * StoresController implements the CRUD actions for Stores model.
 */
class StoresController extends Controller
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
     * Lists all Stores models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StoresSearch();
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
     * Displays a single Stores model.
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
     * Creates a new Stores model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Stores();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            foreach ($_FILES as $type=>$files_array) {
                if ($files_array['error'][0] != 4) {
                    $filesInstances = UploadedFile::getInstancesByName($type);

                    foreach ($filesInstances as $file_up) {
                        $modelPics = new Files();
                        $modelPics->file = $file_up;
                        $filename = time() . Yii::$app->getSecurity()->generateRandomString(5);
                        $modelPics->file->saveAs('uploads/stores/' . $type . '/' . $filename . '.' . $file_up->extension);
                        $modelPics->type = $type;
                        $modelPics->table_name = 'stores';
                        $modelPics->name = $file_up->name;
                        $modelPics->link_id = $model->id;
                        $modelPics->url = '/uploads/stores/' . $type . '/' . $filename . '.' . $file_up->extension;
//                        $modelPics->save();

                        if ($type == 'end_of_work') {
                            $modelPics->save(false);
                        } else {
                            $modelPics->save();
                        }
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
//                'modelPics' => $modelPics,
                'getParams' => Yii::$app->request->get(),
            ]);
        }
    }

    /**
     * Updates an existing Stores model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            foreach ($_FILES as $type=>$files_array) {
                if ($files_array['error'][0]!=4) {
                    $filesInstances = UploadedFile::getInstancesByName($type);

                    foreach ($filesInstances as $file_up) {

                        $modelPics = new Files();
                        $modelPics->file = $file_up;
                        $filename = time() . Yii::$app->getSecurity()->generateRandomString(5);
                        $modelPics->file->saveAs('uploads/stores/' . $type . '/' . $filename . '.' . $file_up->extension);
                        $modelPics->type = $type;
                        $modelPics->table_name = 'stores';
                        $modelPics->name = $file_up->name;
                        $modelPics->link_id = $model->id;
                        $modelPics->url = '/uploads/stores/' . $type . '/' . $filename . '.' . $file_up->extension;
//                        $modelPics->save();

                        if ($type == 'end_of_work') {
                            $modelPics->save(false);
                        } else {
                            $modelPics->save();
                        }
                    }
//                    die();
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
     * Deletes an existing Stores model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);

        foreach ($model->files as $file) {
            if ($file->table_name=='stores') {
                if ((@unlink(Yii::getAlias('@webroot') . $file->url))||(!file_exists($_SERVER['DOCUMENT_ROOT'].$file->url))) {
                    $file->delete();
                }
            }
        }

        foreach ($model->equipment as $equipment) {
            $equipment->delete();
        }

        foreach ($model->works as $work) {
            $works_files=Files::find()->where(['AND', ['link_id' => $work->id], ['table_name' => 'works']]);
            foreach ($works_files->all() as $works_file) {
                if (@unlink(Yii::getAlias('@webroot') . $works_file->url)) {
//                    $works_file->delete();
                }
            }

            Files::deleteAll(['AND', ['link_id' => $work->id], ['table_name' => 'works']]);

            $work->delete();
        }

        foreach ($model->accident as $accident) {
            $accident->delete();
        }

        $model->delete();

        return $this->redirect(['index']);
    }


    /**
     * Finds the Stores model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Stores the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Stores::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
