<?php
/**
 * Created by PhpStorm.
 * User: max-m
 * Date: 02.02.2018
 * Time: 11:49
 */
namespace app\controllers;

use app\models\Logs;
use app\models\User;
use Yii;
use app\models\Files;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class FilesController extends Controller
{
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

    public function actionIndex()
    {
        $searchModel = new FilesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        $type = 'app\\models\\'.ucfirst($model->table_name);

        $linked_class = new $type;
        $linked_model = $linked_class::findOne($model->link_id);

        return $this->render('view', [
            'model' => $model,
            'linked_model' => $linked_model
        ]);
    }

    /**
     * Creates a new Files model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Files();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'getParams' => Yii::$app->request->get(),
            ]);
        }
    }

    /**
     * Updates an existing Files model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Files model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        if ($model->url) {
            if (file_exists($_SERVER['DOCUMENT_ROOT'].$model->url)) {
                if (@unlink(Yii::getAlias('@webroot') . $model->url)) {
                    if ($model->delete()) return true;
                    else return false;
                } else return false;
            } else {
                if ($model->delete()) return true;
                else return false;
            }
        } else return false;
    }

    public function actionDeleteImage()
    {
        $post_fields=Yii::$app->request->post();
        $model=$this->findModel($post_fields['id']);
        if ($model->url) {
            if (file_exists($_SERVER['DOCUMENT_ROOT'].$model->url)) {
                if (@unlink(Yii::getAlias('@webroot') . $model->url)) {
//                    if ($model->delete()) return true;
//                    else return false;
                } //else return false;

                if ($model->delete()) return true;
            } else {
                if ($model->delete()) return true;
                else return false;
            }
        } else return false;
    }

    /**
     * Finds the Files model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Files the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Files::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
?>