<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EngineersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Engineers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="engineers-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?if (!empty($shown_cols)) {
    $decoded_shown_cols = json_decode($shown_cols);
    if (!empty($decoded_shown_cols->engineers)) {
    $index_shown_cols = explode(',', $decoded_shown_cols->engineers);?>
    <div id="db_cols" class="<?foreach ($index_shown_cols as $index_shown_col) echo "show".$index_shown_col.' ';?>">
        <?}
        }?>
    <p>
        <?= Html::a('Добавить инженера', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'cities_name',
                'label' => 'City',
                'format' => 'raw',
                'value' =>function (\app\models\Engineers $data) {
                    if (!empty($data->cities->name)) {
                        return Html::a(
                            $data->cities->name,
                            Url::to(['cities/view', 'id'=>$data->cities->id]),
                            [
                                'title' => $data->cities->name,
                                'target' => '_blank'
                            ]
                        );
                    } else return '';
                },
            ],
            'name',
            'tel1',
            'tel2',
             'email1:email',
             'email2:email',
             'url:url',
             'company_name',
             'tariff',
            [
                'attribute' => 'payment_target',
                'format' => 'text',
                'filter'=>array('card'=>'На карту','cardPhone'=>'На карту, привязанную к телефону','wallet'=>'На кошелёк','walletEl'=>'На электронный кошелёк'),
                'value' =>function (\app\models\Engineers $data) {
                    if (!empty($data->payment_target)) {
                        $vars = array('card'=>'На карту','cardPhone'=>'На карту, привязанную к телефону','wallet'=>'На кошелёк','walletEl'=>'На электронный кошелёк');
                        if (!empty($vars[$data->payment_target])) $payment_target = $vars[$data->payment_target];
                    }
                    if (empty($payment_target)) $payment_target = '—';
                    return $payment_target;
                },
            ],
             'payment',
             'note:ntext',
            [
                'attribute' => 'works_works',
                'label' => 'Работы',
                'format' => 'raw',
                'value' =>function (\app\models\Engineers $data) {
                    $return_works='';
                    foreach ($data->works as $work) {
                        $return_works.=Html::a(
                                ((!empty($work->full_work_performed)) ? $work->full_work_performed:"$work->reason").'('.$work->start_date.((!empty($work->pause_date)) ? " ... ".$work->pause_date." ... ":"").((!empty($work->completion_date)) ? " — ".$work->completion_date:"").')',
                                Url::to(['works/view', 'id'=>$work->id]),
                                [
                                    'title' => $work->full_work_performed,
                                    'target' => '_blank'
                                ]
                            ).'<br><hr>';
                    }
                    $return_works.='<a class="btn btn-success" href="/works/create?engineer_id='.$data->id.'&city_id='.$data->city_id.'">Создать работу</a>';
                    return $return_works;
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
            'id' => 'engineers'
        ],
    ]); ?>
</div>
