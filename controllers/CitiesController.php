<?php

namespace app\controllers;

use Yii;
use app\models\Cities;
use app\models\CitiesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;

/**
 * CitiesController implements the CRUD actions for Cities model.
 */
class CitiesController extends Controller
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
     * Lists all Cities models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CitiesSearch();
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
     * Displays a single Cities model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'user' => $_SESSION['user'],
        ]);
    }

    /**
     * Creates a new Cities model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cities();
        $rates = $this->findModel(4);

        if ($model->load(Yii::$app->request->post())) {
            $model->rate_work=$rates->rate_work;
            $model->rate_hol=$rates->rate_hol;
            $model->rate_depart_work=$rates->rate_depart_work;
            $model->rate_depart_hol=$rates->rate_depart_hol;

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'user' => $_SESSION['user'],
            ]);
        }
    }

    /**
     * Updates an existing Cities model.
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
                'user' => $_SESSION['user'],
            ]);
        }
    }

    public function actionUpdateRates()
    {
        $model = $this->findModel(4);

        if (!empty($ratesPost=Yii::$app->request->post())) {

            foreach ($ratesPost as $ratesName=>$ratesVal) if (!empty($ratesVal)) $ratesPost[$ratesName]=$ratesVal;

            $searchModels=$this->searchAllCities();
            foreach ($searchModels as $searchModel) {
                if ($searchModel->id==8) {
                    $searchModel->rate_work=$ratesPost['krasnodar_rate_work'];
                    $searchModel->rate_hol=$ratesPost['krasnodar_rate_hol'];
                    $searchModel->rate_depart_work=$ratesPost['krasnodar_rate_depart_work'];
                    $searchModel->rate_depart_hol=$ratesPost['krasnodar_rate_depart_hol'];
                    $searchModel->save();
                } else {
                    $searchModel->rate_work=$ratesPost['Cities']['rate_work'];
                    $searchModel->rate_hol=$ratesPost['Cities']['rate_hol'];
                    $searchModel->rate_depart_work=$ratesPost['Cities']['rate_depart_work'];
                    $searchModel->rate_depart_hol=$ratesPost['Cities']['rate_depart_hol'];
                    $searchModel->save();
                }
            }
        }


        return $this->render('update_admin', [
            'model' => $model,
            'user' => $_SESSION['user'],
            'krasnodar' => $this->findModel(8),
        ]);
    }

    /**
     * Deletes an existing Cities model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cities model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cities the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cities::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function searchAllCities()
    {
        $query = Cities::find()->all();
        return $query;
    }

}
