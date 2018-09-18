<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\WorksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Works';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="works-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?if (!empty($shown_cols)) {
    $decoded_shown_cols = json_decode($shown_cols);
    if (!empty($decoded_shown_cols->works)) {
    $index_shown_cols = explode(',', $decoded_shown_cols->works);?>
    <div id="db_cols" class="<?foreach ($index_shown_cols as $index_shown_col) echo "show".$index_shown_col.' ';?>">
        <?}
        }?>
    <p>
        <?= Html::a('Добавить работу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?
    $columns=[//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'accident_id',
        [
            'attribute' => 'this_work',
            'label' => 'This work',
            'format' => 'raw',
            'value' =>function (\app\models\Works $data) {
                return Html::a(
                    'Work\'s link',
                    Url::to(['works/view', 'id' => $data->id]),
                    [
                        'title' => 'This work',
                        'target' => '_blank'
                    ]
                );
            },
        ],
        [
            'attribute' => 'accident_acc_id',
            'label' => 'Accident ID',
            'format' => 'raw',
            'value' =>function (\app\models\Works $data) {
                if (!empty($data->accident->acc_id)) {
                    return Html::a(
                        $data->accident->acc_id,
                        Url::to(['accident/view', 'id' => $data->accident->id]),
                        [
                            'title' => $data->accident->acc_id,
                            'target' => '_blank'
                        ]
                    );
                } else return '';
            },
        ],
//            'start_date',
        [
            'attribute' => 'start_date',
            'value' =>function (\app\models\Works $data) {
                if (!empty($data->start_date)) return date('d.m.Y', strtotime($data->start_date));
                else return '';
            },
        ],
        [
            'attribute' => 'pause_date',
            'value' =>function (\app\models\Works $data) {
                if (!empty($data->pause_date)) return date('d.m.Y', strtotime($data->pause_date)).((!empty($data->end_pause_date))?' — '.date('d.m.Y', strtotime($data->end_pause_date)):'').((!empty($data->reason))?' ('.$data->reason.')':'');
                else return '';
            },
        ],
        [
            'attribute' => 'completion_date',
            'value' =>function (\app\models\Works $data) {
                if (!empty($data->completion_date)) return date('d.m.Y', strtotime($data->completion_date));
                else return '';
            },
        ],
        [
            'attribute' => 'act_time_hour',
            'value' =>function (\app\models\Works $data) {
                if (!empty($data->act_time_hour)) {
                    $time=explode('.', $data->act_time_hour);
                    if (empty($time[1])) $min='00';
                    else $min='30';
                    return $time[0].':'.$min;
                } else return '';
            },
        ],
        'act_time',
        'real_time',
        'full_work_performed:ntext',
        'own_equip_sum',
        'full_price',
        [
            'attribute' => 'engineers_name',
            'label' => 'Engineer',
            'format' => 'raw',
            'value' =>function (\app\models\Works $data) {
                if (!empty($data->engineers->name)) {
                    return Html::a(
                        $data->engineers->name,
                        Url::to(['engineers/view', 'id' => $data->engineers->id]),
                        [
                            'title' => $data->engineers->name,
                            'target' => '_blank'
                        ]
                    );
                } else return '';
            },
        ],
        'summary',
        [
            'attribute' => 'payment',
            'value' =>function (\app\models\Works $data) {
                if (!empty($data->payment)) {
                    if ($data->payment == 1) return 'ДА';
                    else return 'ОЖИДАЕТ';
                } else return 'Нет';
            },
        ],
        'status',
        // 'note:ntext',
        // 'act_pdf',
        // 'act_scan',
        // 'pics',

//        ['class' => 'yii\grid\ActionColumn'],
    ];
    /*if ($user_id==101) {
        $columns[]='rate';
        $columns[]='full_price';
        $columns[]=['class' => 'yii\grid\ActionColumn',
            'template' => '{view}<br>{update}<br>{delete}<br>{calculate}',
            'buttons' => [
                'calculate' => function($model, $key, $index) {
                    if(($key->status=='ОПЛАЧЕН')||($key->status=='ЗАВЕРШЕН')) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-euro"></span>',
                            Url::to(['works/calculate-work', 'id'=>$key->id]),
                            [
                                'title' => 'Посчитать на дату закрытия',
                                'class' => 'calculate_work'
                            ]);
                    }
                }
            ]
        ];
    }*/?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
            'id' => 'works'
        ],
    ]); ?>
</div>
