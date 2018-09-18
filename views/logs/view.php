<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Logs */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logs-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'accident.acc_id',
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
            [
                'attribute' => 'user.username',
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
                'format' => 'raw',
                'value' =>function (\app\models\Logs $data) {
                    $user_id=Yii::$app->user->getId();
                    $changes_obj = json_decode($data->text);

                    $return_works='<table class="works_table"><thead style="font-weight:bold"><tr><td>Field</td><td>From</td><td>To</td></tr></thead><tbody>';
                    foreach ($changes_obj as $field_name=>$field_values) {
                        $return_works.='<tr><td>'.$field_name.'</td><td>'.$field_values->from.'</td><td>'.$field_values->to.'</td>';
                    }

                    $return_works.='</tbody></table>';
                    return $return_works;
                },
            ],
            [
                'attribute' => 'created',
                'value' =>function (\app\models\Logs $data) {
                    if (!empty($data->created)) return date('d.m.Y h:i:s', strtotime($data->created));
                    else return '';
                },
            ]
        ],
    ]) ?>

</div>
