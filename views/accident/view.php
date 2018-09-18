<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\models\Works;

/* @var $this yii\web\View */
/* @var $model app\models\Accident */

$this->title = $model->acc_id.((!empty($model->fault)) ? ' ('.$model->fault.')' : '');
$this->params['breadcrumbs'][] = ['label' => 'Accident', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/css/im.css');
?>
<div class="accident-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить инцидент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?
    $attributes = [
//            'id',
        'acc_id',
        'act_number',
        [
            'attribute' => 'act_date',
            'value' =>function (\app\models\Accident $data) {
                if (!empty($data->act_date)) return date('d.m.Y', strtotime($data->act_date));
                else return '';
            },
        ],
        'priority',
        [
            'attribute' => 'stores.store_name',
            'label' => 'Store',
            'format' => 'raw',
            'value' =>function (\app\models\Accident $data) {
                if (!empty($data->stores->store_name)) {
                    return Html::a(
                        $data->stores->store_name,
                        Url::to(['stores/view', 'id'=>$data->stores->id]),
                        [
                            'title' => $data->stores->store_name,
                            'target' => '_blank'
                        ]
                    );
                } else return '';
            },
        ],
        [
            'attribute' => 'cities.name',
            'label' => 'City',
            'format' => 'raw',
            'value' =>function (\app\models\Accident $data) {
                if (!empty($data->cities->name)) {
                    return Html::a(
                        $data->cities->name,
                        Url::to(['cities/view', 'id' => $data->cities->id]),
                        [
                            'title' => $data->cities->name,
                            'target' => '_blank'
                        ]
                    );
                }
            },
        ],
        [
            'attribute' => 'equipment.equip',
            'label' => 'Equipment',
            'format' => 'raw',
            'value' =>function (\app\models\Accident $data) {
                if (!empty($data->equipment->equip)) {
                    return Html::a(
                        $data->equipment->equip,
                        Url::to(['equipment/view', 'id'=>$data->equipment->id]),
                        [
                            'title' => $data->equipment->equip,
                            'target' => '_blank'
                        ]
                    );
                } else return '';
            },
        ],
        [
            'attribute' => 'responsibleUser.username',
            'label' => 'Responsible',
            'format' => 'html',
            'value' =>function (\app\models\Accident $data) {
                if (!empty($data->responsibleUser->username)) {
                    return $data->responsibleUser->username;
                }
            },
        ],
        [
            'attribute' => 'accountantUser.username',
            'label' => 'Accountant',
            'format' => 'html',
            'value' =>function (\app\models\Accident $data) {
                if (!empty($data->accountantUser->username)) {
                    return $data->accountantUser->username;
                }
            },
        ],
        [
            'attribute' => 'works_works',
            'label' => 'Works',
            'format' => 'html',
            'value' =>function (\app\models\Accident $data) {
//                $user_id=Yii::$app->user->getId();
                $return_works='<table class="works_table">';
                foreach ($data->works as $work) {
                    $return_works.=
                        '<tr><td>'.
                        Html::a(((!empty($work->full_work_performed)) ? $work->full_work_performed:"$work->reason"),
                            Url::to(['works/view', 'id'=>$work->id]),
                            [
                                'title' => $work->full_work_performed,
                                'target' => '_blank'
                            ]
                        ).
                        '</td><td>'.
                        date('d.m.Y', strtotime($work->start_date)).((!empty($work->pause_date)) ? "<br>...".date('d.m.Y', strtotime($work->pause_date)):"").((!empty($work->completion_date)) ? "<br>".date('d.m.Y', strtotime($work->completion_date)):"").
                        '</td><td'.((!empty($work->full_price)) ? ' colspan="3"':'').'>'.
                        ((!empty($work->status)) ? "<b>".$work->status."</b>":"").
                        '</td>';
//                    if (($user_group<=2)&&(!empty($work->full_price))) $return_works.='<td>'.$work->rate.'</td><td>'.$work->full_price.'</td>';
                    $return_works.='</tr>';
                }
                $return_works.='</table>';
                if((!empty($data->stores->city_id))&&($data->acc_status!='CARRIED_OUT')) $return_works.='<br><a class="btn btn-success" href="/works/create?accident_id='.$data->id.'&city_id='.$data->stores->city_id.'">Создать работу</a>';
                return $return_works;
            },
        ],
        'fault',
        [
            'attribute' => 'acc_status',
            'label' => 'Статус',
            'format' => 'html',
            'value' =>function (\app\models\Accident $data) {
                $flag=1;
                foreach ($data->works as $work) if ($work->status!='ОПЛАЧЕН') $flag=0;

                if (!empty($data->acc_status)) {
                    $status_return=$data->acc_status;
                } else $status_return='ОТКРЫТО';

                if (($flag==1)&&($data->acc_status != 'CARRIED_OUT')) {
                    if ($status_return=='OPEN') $status_return.='</b><br>'.Html::a(
                            '<span class="glyphicon glyphicon-ok"></span>',
                            Url::to(['accident/set-status', 'id'=>$data->id, 'status'=>'CLOSED']),
                            [
                                'title' => 'Закрыть инцидент',
                                'class' => 'change_status'
                            ]).'     '.Html::a(
                        '<span class="glyphicon glyphicon-remove-circle"></span>',
                        Url::to(['accident/set-status', 'id'=>$data->id, 'status'=>'CANCELED']),
                        [
                            'title' => 'Отменить инцидент',
                            'class' => 'change_status'
                        ]);
                    else $status_return.='</b><br>'.Html::a(
                            '<span class="glyphicon glyphicon-share-alt"></span>',
                            Url::to(['accident/set-status', 'id'=>$data->id, 'status'=>'OPEN']),
                            [
                                'title' => 'Открыть инцидент',
                                'class' => 'change_status'
                            ]);
                } elseif ($data->acc_status == 'CARRIED_OUT') $status_return.='</b>';
                else $status_return='OPEN</b>';


                return '<b>'.$status_return;
            },
        ],
        'note:ntext',
        [
            'attribute' => 'files.url',
            'label' => 'PDF',
            'format' => 'raw',
            'value' =>function (\app\models\Accident $data) {
                $return_pics='';
                foreach ($data->files as $pic) {
                    if ($pic->type=='act_pdf') $return_pics.='<div class="pdf">'.Html::a($pic->name, Url::to(['files/view', 'id'=>$pic->id]), ['class' => 'link-download', 'target' => '_blank']).'</div><br>';
                }
                return $return_pics;
            },
        ],
        [
            'attribute' => 'files.url',
            'label' => 'Изображения',
            'format' => 'raw',
            'value' =>function (\app\models\Accident $data) {
                $return_pics='';
                foreach ($data->files as $pic) {
                    if ($pic->type=='pics') $return_pics.='<div class="pic">'.Html::a(Html::img($pic->url, $options = ['class' => 'postImg', 'style' => ['width' => '360px']]), Url::to(['files/view', 'id'=>$pic->id]), ['class' => 'link-download', 'target' => '_blank']).'</div><br>';
                }
                return $return_pics;
            },
        ],
        [
            'attribute' => 'mail_string',
            'label' => 'Сформированная строка',
            'format' => 'raw',
            'value' =>function (\app\models\Accident $data) {
                $flag=true;
                $return_data='';
                if (!empty($data->act_date)) $return_data=date('d.m.y', strtotime($data->act_date));
                else $flag=false;

                if (!empty($data->acc_id)) $return_data.=' '.$data->acc_id;
                else $flag=false;

                if (!empty($data->stores->store_name)) $return_data.=' '.$data->stores->store_name;
                else $flag=false;

                if (!empty($data->stores->inner_id)) $return_data.=' '.$data->stores->inner_id;
                else $flag=false;

                if (!empty($data->act_number)) $return_data.=' '.$data->act_number;
                else $flag=false;

                if (!empty($data->stores->address)) $return_data.='<br>'.$data->stores->address;
                else $flag=false;

                if (!empty($data->fault)) $return_data.='<br>'.$data->fault;
                else $flag=false;

                if (!empty($data->equipment->equip)) $return_data.=' '.$data->equipment->equip;
                else $flag=false;

                if (!empty($data->equipment->player_id)) $return_data.=' PlayerID:'.$data->equipment->player_id;
                if (!empty($data->equipment->net_cable_name)) $return_data.=' LastIP:'.$data->equipment->net_cable_name;

                if ($flag) return '<div class="copy_block"><span>'.$return_data.'</span><a class="glyphicon glyphicon-duplicate" title="Копировать"></a>';
                else return '';
            },
        ]
    ];

    $attributes[]=[
        'label' => 'Логи',
        'format' => 'raw',
        'value' =>function (\app\models\Accident $data) {
            $options = ['class' => 'logs_table', 'data' => ['acc' => $data->id]];
            $res = Html::tag('div', '', $options);

            $res .= '<form class="add_message" action="/logs/create-message">
                <input type="hidden" name="accident_id" value="'.$data->id.'">
                <div class="row"><textarea name="text"></textarea></div>
                <div class="row inputfile"><label><span>Прикрепить файл</span><input type="file" name="attach[]"></label></div>
                <div class="row">
                    <ul class="recipients_box">';
//                    foreach ($taking_part as $taking_part_user_id=>$taking_part_user) {
//                        $res .= '<li><input type="checkbox" name="recipients[]" value="'.$taking_part_user_id.'" id="recipient_'.$taking_part_user_id.'"><label for="recipient_'.$taking_part_user_id.'">'.$taking_part_user.'</label>';
//                    }
                    $res .= '</ul>
                </div>
                <div class="row"><button type="submit" class="btn btn-success js-form-success">Отправить сообщение</button></div>
            </form>';
            return $res;
        },
    ];

    /*if ($user_group<=2) {
        $attributes[]=[
            'label' => 'Сумма часов',
            'value' =>function (\app\models\Accident $data) {
                $sum_time=0;
                foreach($data->works as $work) {
                    $sum_time+=$work->act_time_hour;
                }
                return $sum_time;
            },
        ];
        $attributes[]=[
            'label' => 'Сумма, руб.',
            'format' => 'html',
            'value' =>function (\app\models\Accident $data) {
                $sum_rub=0;
                $flag=1;
                foreach($data->works as $work) {
                    if (($work->status != 'ОПЛАЧЕН') && ($work->status != 'ЗАВЕРШЕН')) $flag = 0;
                    $sum_rub+=$work->full_price;
                    if (!empty($work->own_equip_sum)) $sum_rub+=$work->own_equip_sum;
                }
                if (count($data->works)==0) return 'Работы<br>не<br>завершены';
                elseif ($sum_rub>0) return $sum_rub;
                else return 'Требуется<br>пересчит.<br>работы';
            },
        ];
    }*/
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

</div>
