<?php

namespace app\controllers;

use Yii;
use app\models\Accident;
use app\models\AccidentSearch;
use app\models\Files;
use app\models\Stores;
use app\models\Equipment;
use app\models\Logs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\LoginForm;

/**
 * AccidentController implements the CRUD actions for Accident model.
 */
class AccidentController extends Controller
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
     * Lists all Accident models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccidentSearch();
        $queryParams=Yii::$app->request->queryParams;
        if ((!empty($queryParams["AccidentSearch"]["acc_status"]))&&($queryParams["AccidentSearch"]["acc_status"]=='CANCELED')) $queryParams["not_acc_status"]=false;
        else $queryParams["not_acc_status"]=true;

        $dataProvider = $searchModel->search($queryParams);

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
//            'dataProvider' => $dataProvider
//        ]);
    }

    /**
     * Displays a single Accident model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $taking_part = Accident::takingPart($model);

        return $this->render('view', [
            'model' => $model,
            'taking_part' => $taking_part,
            'user' => $_SESSION['user'],
        ]);
    }

    /**
     * Creates a new Accident model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Accident();

        $user_id = $_SESSION['user']['id'];
        $user_group = $_SESSION['user']['user_group'];

        if ($user_group <= 20) {

            if ($model->load(Yii::$app->request->post())) {

                $model = Accident::setDates($model);

                $model->responsible = $user_id;

                if (empty($model->acc_status)) {
                    $model->acc_status = 'ОТКРЫТА';
                }

                $arChanges = Logs::getChanges($model);

                if ($model->save()) {

                    $file_changes = Files::saveFiles('accident', $model->id, $_FILES);
                    if (!empty($file_changes)) $arChanges += $file_changes;

                    Logs::createLog('ACCIDENT CREATE', $user_id, $arChanges, $model->id);

                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                        'getParams' => Yii::$app->request->get(),
                        'user' => $_SESSION['user'],
                    ]);
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'getParams' => Yii::$app->request->get(),
                    'user' => $_SESSION['user'],
                ]);
            }
        }
    }

    /**
     * Updates an existing Accident model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $user_id = $_SESSION['user']['id'];
        $user_group = $_SESSION['user']['user_group'];

        if ($user_group <= 20) {
            if ($model->load(Yii::$app->request->post())) {

                $model = Accident::setDates($model);

                $arChanges = Logs::getChanges($model);

                if ($model->save()) {

                    $file_changes = Files::saveFiles('accident', $model->id, $_FILES);
                    if (!empty($file_changes)) $arChanges += $file_changes;

                    Logs::createLog('ACCIDENT UPDATE', $user_id, $arChanges, $model->id);

                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    return $this->render('update', [
                        'model' => $model,
                        'user' => $_SESSION['user'],
                    ]);
                }
            } else {

                if (empty($model->acc_status)) $model->acc_status = '';

//            if (($user_group<2)&&($model->acc_status=='ПРОВЕДЕНО'))  return $this->render('view', [
//                'model' => $this->findModel($id),
//                'user_group' => $user_group,
//            ]);
//            else
                return $this->render('update', [
                    'model' => $model,
                    'user' => $_SESSION['user'],
                ]);
            }
        }
    }

    /**
     * Deletes an existing Accident model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $user_id = $_SESSION['user']['id'];
        $user_group = $_SESSION['user']['user_group'];

        if (empty($model->acc_status)) $model->acc_status='';

        if ($model->acc_status!='ПРОВЕДЕНО') {

            $arChanges=Logs::getLastChanges($model);

            if(!empty($model->files)) foreach ($model->files as $file) {
                if ($file->table == 'accident') {
                    $arChanges[$file->type]=array('from'=>'existed files', 'to'=>'');

                    if ((@unlink(Yii::getAlias('@webroot') . $file->url)) || (!file_exists($_SERVER['DOCUMENT_ROOT'] . $file->url))) {
                        $file->delete();
                    }
                }
            }

            $arChanges['works']=array('from'=>'', 'to'=>'');
            if(!empty($model->works)) foreach ($model->works as $work) {
                $arChanges['works']['from'].=((!empty($work->full_work_performed)) ? $work->full_work_performed:"$work->reason").'; ';

                $works_files = Files::find()->where(['AND', ['link_id' => $work->id], ['table_name' => 'works']]);
                foreach ($works_files->all() as $works_file) {
                    if (@unlink(Yii::getAlias('@webroot') . $works_file->url)) {
//                        $works_file->delete();
                    }
                }

                Files::deleteAll(['AND', ['link_id' => $work->id], ['table_name' => 'works']]);

                $work->delete();
            }
            if (empty($arChanges['works']['from'])) unset($arChanges['works']);

            Logs::createLog('ACCIDENT DELETE', $user_id, $arChanges, $model->id);

            $model->delete();

        }
        
        return $this->redirect(['index']);
    }


    public function actionGetStores()
    {
        $post_fields=Yii::$app->request->post();

        $model_stores = Stores::find()->where(['city_id'=>$post_fields['val']])->all();

        return $this->renderPartial('stores', [
            'model' => $model_stores,
        ]);
    }

    public function actionGetEquip()
    {
        $post_fields=Yii::$app->request->post();

        $model_equip = Equipment::find()->where(['store_id'=>$post_fields['val']])->all();

        return $this->renderPartial('equips', [
            'model' => $model_equip,
        ]);
    }

    public function actionSetStatus($id, $status=false)
    {
        $model=$this->findModel($id);

        $model = Accident::setStatus($model);

        return $this->render('view', [
            'model' => $model,
            'user' => $_SESSION['user'],
        ]);
    }

    /**
     * Finds the Accident model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Accident the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Accident::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
