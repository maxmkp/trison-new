<?php

namespace app\controllers;


use Yii;
use app\models\Works;
use app\models\WorksSearch;
use app\models\Files;
use app\models\Accident;
use app\models\Stores;
use app\models\Engineers;
use app\models\Logs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\LoginForm;

/**
 * WorksController implements the CRUD actions for Works model.
 */
class WorksController extends Controller
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
     * Lists all Works models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (!Yii::$app->user->isGuest) {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'user' => $_SESSION['user'],
                'shown_cols' => $_SESSION['user']['shown_cols'],
            ]);
        } else {
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->goBack();
            }
            return $this->render('login', [
                'model' => $model,
            ]);
        }

//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);

    }

    /**
     * Displays a single Works model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $taking_part = Works::takingPart($model);

        return $this->render('view', [
            'model' => $model,
            'taking_part' => $taking_part,
            'user' => $_SESSION['user'],
        ]);
    }

    /**
     * Creates a new Works model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Works();
        $user_id = $_SESSION['user']['id'];
        $user_group = $_SESSION['user']['user_group'];

        if ($model->load(Yii::$app->request->post())) {

            $model = Works::setDates($model);

            if (!empty($model->accident_id)) {
                $acc_model = Accident::findOne($model->accident_id);
                $acc_status = $acc_model->acc_status;
                $acc_id = $model->accident->id;
//                $model = Works::setStatus($model, $acc_model);
            } else {
                $acc_status = '';
                $acc_id = false;
//                $model = Works::setStatus($model);
            }

            if (($acc_status!='ПРОВЕДЕНО')&&($acc_status!='ВЫГРУЖЕНО')) {
                $arChanges=Logs::getChanges($model);
                if ($model->save()) {

                    $file_changes = Files::saveFiles('works', $model->id, $_FILES);
                    if (!empty($file_changes)) $arChanges+=$file_changes;

                    $model = Works::checkStatus($model);

//                    foreach ($_FILES as $type => $files_array) {
//                        if ($files_array['error'][0] != 4) {
//                            $arChanges[$type] = array('from' => '', 'to' => 'new file uploaded');
//                            $filesInstances = UploadedFile::getInstancesByName($type);
//
//                            foreach ($filesInstances as $file_up) {
//                                $modelPics = new Files();
//                                $modelPics->file = $file_up;
//                                $filename = time() . Yii::$app->getSecurity()->generateRandomString(5);
//                                $modelPics->file->saveAs('uploads/works/' . $type . '/' . $filename . '.' . $file_up->extension);
//                                $modelPics->type = $type;
//                                $modelPics->table_name = 'works';
//                                $modelPics->name = $file_up->name;
//                                $modelPics->link_id = $model->id;
//                                $modelPics->url = '/uploads/works/' . $type . '/' . $filename . '.' . $file_up->extension;
//                                if ($type == 'act_pdf') {
//                                    $modelPics->save(false);
//                                } else {
//                                    $modelPics->save();
//                                }
//
//                            }
//                        }
//                    }

                    Logs::createLog('WORK CREATE', $user_id, $arChanges, $acc_id, $model->id);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
//                'modelPics' => $modelPics,
                'getParams' => Yii::$app->request->get(),
                'user_group' => $user_group,
            ]);
        }
    }

    /**
     * Updates an existing Works model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user_id = $_SESSION['user']['id'];
        $user_group = $_SESSION['user']['user_group'];

        if ($model->load(Yii::$app->request->post())) {

            $model = Works::setDates($model);

            if (!empty($model->accident_id)) {
                $acc_model = Accident::findOne($model->accident_id);
                $acc_status = $acc_model->acc_status;
                $acc_id = $model->accident->id;
//                $model = Works::setStatus($model, $acc_model);
            } else {
                $acc_status = '';
                $acc_id = false;
//                $model = Works::setStatus($model);
            }

            if (($acc_status!='ПРОВЕДЕНО')&&($acc_status!='ВЫГРУЖЕНО')) {
                $arChanges=Logs::getChanges($model);
                if ($model->save()) {
                    $file_changes = Files::saveFiles('works', $model->id, $_FILES);
                    if (!empty($file_changes)) $arChanges+=$file_changes;

                    $model = Works::checkStatus($model);

//                    foreach ($_FILES as $type => $files_array) {
////                        if ($files_array['error'][0] != 4) {
//                            $arChanges[$type]=array('from'=>'', 'to'=>'new file uploaded');
//                            $filesInstances = UploadedFile::getInstancesByName($type);
//                            foreach ($filesInstances as $file_up) {
////                                $modelPics = new Files();
//                                $modelPics->file = $file_up;
//                                $filename = time() . Yii::$app->getSecurity()->generateRandomString(5);
//                                $modelPics->file->saveAs('uploads/works/' . $type . '/' . $filename . '.' . $file_up->extension);
//                                $modelPics->type = $type;
//                                $modelPics->table_name = 'works';
//                                $modelPics->name = $file_up->name;
//                                $modelPics->link_id = $model->id;
//                                $modelPics->url = '/uploads/works/' . $type . '/' . $filename . '.' . $file_up->extension;
//                                if ($type == 'act_pdf') {
//                                    $modelPics->save(false);
//                                } else {
//                                    $modelPics->save();
//                                }
//
//                            }
//                        }
//                    }

                    Logs::createLog('WORK UPDATE', $user_id, $arChanges, $acc_id, $model->id);

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {

            if (empty($model->accident->acc_status)) $model->accident->acc_status='';

            if ($model->accident->acc_status=='CARRIED_OUT') {

                return $this->render('view', [
                    'model' => $model,
                    'user_group' => $user_group,
                ]);

            } else {

                return $this->render('update', [
                    'model' => $model,
                    'user_group' => $user_group,
                ]);

            }
        }
    }

    /**
     * Deletes an existing Works model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        $model=$this->findModel($id);
        $user_id = $_SESSION['user']['id'];
        $user_group = $_SESSION['user']['user_group'];

        if (!empty($model->accident_id)) {
            $acc_model = Accident::findOne($model->accident_id);
            $acc_status = $acc_model->acc_status;
            $acc_id = $model->accident->id;
        } else {
            $acc_status = '';
            $acc_id = false;
        }

        if ($acc_status!='CARRIED_OUT') {

            $arChanges=Logs::getLastChanges($model);

            if (!empty($model->files)) foreach ($model->files as $file) {
                if (($file->type == 'act_pdf') || ($file->type == 'act_scan') || ($file->type == 'pics')) {

                    $arChanges[$file->type]=array('from'=>'existed files', 'to'=>'');

                    if ((@unlink(Yii::getAlias('@webroot') . $file->url)) || (!file_exists($_SERVER['DOCUMENT_ROOT'] . $file->url))) {
                        $file->delete();
                    }
                }
            }

            Logs::createLog('WORK DELETE', $user_id, $arChanges, $acc_id, $model->id);

            $model->delete();
            return $this->redirect(['index']);
        }
    }

    public function actionGetEngineers()
    {
        $post_fields=Yii::$app->request->post();

        $model_city = Accident::find()->where(['id'=>$post_fields['val']])->one();
        $model_stores = Stores::find()->where(['id'=>$model_city->store_id])->one();
//        print_r($model_city);
        $model_engineers = Engineers::find()->where(['city_id'=>$model_stores->city_id])->all();

        return $this->renderPartial('engineers', [
            'model' => $model_engineers,
        ]);
    }

    public function actionCalculateAllWork($id) {
        Works::actionCalculateAccidentWork($id);

        return $this->redirect(['accident/index?sort=-act_date']);
    }

    public function actionCalculateWork($id, $skip=false) {
        $model=$this->findModel($id);

        Works::actionCalculateWork($model);

        return $this->actionIndex();
    }

    /**
     * Finds the Works model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Works the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Works::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested works do not exist.');
        }
    }

    protected function findModelsByAccident($id)
    {
        $models = Works::find()->where(['accident_id' => $id])->all();

        if ($models !== null) {
            return $models;
        } else {
            throw new NotFoundHttpException('The requested works do not exist.');
        }
    }

}
