<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?if (!empty($shown_cols)) {
    $decoded_shown_cols = json_decode($shown_cols);
    if (!empty($decoded_shown_cols->logs)) {
    $index_shown_cols = explode(',', $decoded_shown_cols->logs);?>
    <div id="db_cols" class="<?foreach ($index_shown_cols as $index_shown_col) echo "show".$index_shown_col.' ';?>">
        <?}
        }?>
    <p>
        <?= Html::a('Create Logs', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
//            'accident_id',
            [
                'attribute' => 'accident_acc_id',
                'label' => 'Accident_id',
                'format' => 'raw',
                'value' =>function (\app\models\Logs $data) {
                    if (!empty($data->accident->acc_id)) {
                        return Html::a(
                            $data->accident->acc_id,
                            Url::to(['accident/view', 'id' => $data->accident->id]),
                            [
                                'title' => $data->accident->acc_id,
                                'target' => '_blank'
                            ]
                        );
                    }
                },
            ],
            [
                'attribute' => 'works_works',
                'label' => 'Работы',
                'format' => 'raw',
                'value' =>function (\app\models\Logs $data) {
                    $user_id=Yii::$app->user->getId();
                    $return_works='<table class="works_table">';

                    if(!empty($data->works)) {
                        $work = $data->works;

                        $return_works .=
                            '<tr><td>' .
                            Html::a(((!empty($work->full_work_performed)) ? $work->full_work_performed : "$work->reason"),
                                Url::to(['works/view', 'id' => $work->id]),
                                [
                                    'title' => $work->full_work_performed,
                                    'target' => '_blank'
                                ]
                            ) .
                            '</td><td>' .
                            date('d.m.Y', strtotime($work->start_date)) . ((!empty($work->pause_date)) ? "<br>..." . date('d.m.Y', strtotime($work->pause_date)) : "") . ((!empty($work->completion_date)) ? "<br>" . date('d.m.Y', strtotime($work->completion_date)) : "") .
                            '</td><td' . ((!empty($work->full_price)) ? ' colspan="3"' : '') . '>' .
                            ((!empty($work->status)) ? "<b>" . $work->status . "</b>" : "") .
                            '</td>';
                        if (($user_id == 101) && (!empty($work->full_price))) $return_works .= '<td>' . $work->rate . '</td><td>' . $work->full_price . '</td>';
                        $return_works .= '</tr>';

                    }

                    $return_works.='</table>';
                    if((!empty($data->stores->city_id))&&($data->acc_status!='CARRIED_OUT')) $return_works.='<br><a class="btn btn-success" href="/works/create?accident_id='.$data->id.'&city_id='.$data->stores->city_id.'">Создать работу</a>';
                    return $return_works;
                },
            ],
//            'work_id',
            [
                'attribute' => 'user_username',
                'label' => 'User',
                'format' => 'raw',
                'value' =>function (\app\models\Logs $data) {
                    if (!empty($data->user->username)) {
                        return Html::a(
                            $data->user->username,
                            Url::to(['accident/view', 'id' => $data->user->id]),
                            [
                                'title' => $data->user->username,
                                'target' => '_blank'
                            ]
                        );
                    }
                },
            ],
            'act_type',
            [
                'attribute' => 'changes',
                'label' => 'Changes',
                'format' => 'html',
                'value' =>function (\app\models\Logs $data) {
                    if(!empty($data->fields_json)) {
                        $changes_obj = json_decode($data->fields_json);

                        if (!empty($changes_obj)) {

                            $return_works = '<table class="works_table"><thead style="font-weight:bold"><tr><td>Field</td><td>From</td><td>To</td></tr></thead><tbody>';
                            foreach ($changes_obj as $field_name => $field_values) {
                                $return_works .= '<tr><td>' . $field_name . '</td><td>' . $field_values->from . '</td><td>' . $field_values->to . '</td>';
                            }

                            $return_works .= '</tbody></table>';
                        } else $return_works = '';
                        return $return_works;
                    }
                },
            ],
            'text',
            [
                'attribute' => 'created',
                'value' =>function (\app\models\Logs $data) {
                    if (!empty($data->created)) return date('d.m.Y h:i:s', strtotime($data->created));
                    else return '';
                },
            ],
//            'text:ntext',
            //'created',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
            'id' => 'logs'
        ],
    ]); ?>
</div>
