<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\models\Works;
use app\models\Files;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccidentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accidents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accident-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?if (!empty($shown_cols)) {
    $decoded_shown_cols = json_decode($shown_cols);
    if (!empty($decoded_shown_cols->accident)) {
    $index_shown_cols = explode(',', $decoded_shown_cols->accident);?>
    <div id="db_cols" class="<?foreach ($index_shown_cols as $index_shown_col) echo "show".$index_shown_col.' ';?>">
        <?}
        }?>
        <p>
            <?= Html::a('Создать инцидент', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <?$columns = [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//        'acc_id',
            [
                'attribute' => 'acc_id',
                'label' => 'Accident_id',
                'format' => 'raw',
                'value' =>function (\app\models\Accident $data) {
                    if (!empty($data->acc_id)) {
                        return Html::a(
                            $data->acc_id,
                            Url::to(['accident/view', 'id' => $data->id]),
                            [
                                'title' => $data->acc_id,
                                'target' => '_blank'
                            ]
                        );
                    }
                },
            ],
            'act_number',
            [
                'attribute' => 'act_date',
                'value' =>function (\app\models\Accident $data) {
                    if (!empty($data->act_date)) return date('d.m.Y', strtotime($data->act_date));
                    else return '';
                },
            ],
            [
                'attribute' => 'acc_status',
                'label' => 'Статус',
                'format' => 'html',
                'filter'=>array("ОТКРЫТА"=>"ОТКРЫТА", "В РАБОТЕ"=>"В РАБОТЕ", "ВЫГРУЖЕНО"=>"ВЫГРУЖЕНО", "ПРОВЕДЕНО"=>"ПРОВЕДЕНО", "ОШИБОЧНО"=>"ОШИБОЧНО"),
                'value' =>function (\app\models\Accident $data) {
                    if (!empty($data->acc_status)) {
                        $status_return='<span class="STATUS">'.$data->acc_status.'</span>';
                    }// else $status_return='<span class="STATUS">OPEN</span>';

                    //$status_return.="<script>$('tr[data-key=".$data->id."]').css('display', 'none');</script>";

                    return $status_return;
                },
            ],
            [
                'attribute' => 'close_date',
                'value' =>function (\app\models\Accident $data) {
                    if ((!empty($data->close_date))&&($data->close_date!='0000-00-00')) return date('d.m.Y', strtotime($data->close_date));
                    else return '';
                },
            ],
            'priority',
//        [
//            'attribute' => 'countries_name',
//            'label' => 'Country',
//            'format' => 'html',
//            'value' =>function (\app\models\Accident $data) {
//                if (!empty($data->countries->name)) {
//                    return Html::a(
//                        $data->countries->name,
//                        Url::to(['countries/view', 'id' => $data->countries->id]),
//                        [
//                            'title' => $data->countries->name,
//                            'target' => '_blank'
//                        ]
//                    );
//                }
//            },
//        ],
            [
                'attribute' => 'cities_name',
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
                'attribute' => 'stores_store_name',
                'label' => 'Store',
                'format' => 'raw',
                'value' =>function (\app\models\Accident $data) {
                    if (!empty($data->stores->store_name)) {
                        return Html::a(
                            $data->stores->store_name,
                            Url::to(['stores/view', 'id' => $data->stores->id]),
                            [
                                'title' => $data->stores->store_name,
                                'target' => '_blank'
                            ]
                        );
                    }
                },
            ],
            [
                'attribute' => 'equipment_equip',
                'label' => 'Equipment',
                'format' => 'raw',
                'value' =>function (\app\models\Accident $data) {
                    if (!empty($data->equipment->equip)) {
                        return Html::a(
                            $data->equipment->equip,
                            Url::to(['equipment/view', 'id' => $data->equipment->id]),
                            [
                                'title' => $data->equipment->equip,
                                'target' => '_blank'
                            ]
                        );
                    }
                },
            ],
            [
                'attribute' => 'responsibleUser_username',
                'label' => 'Responsible',
                'format' => 'raw',
                'value' =>function (\app\models\Accident $data) {
                    if (!empty($data->responsibleUser->username)) {
                        return $data->responsibleUser->username;
                    }
                },
            ],
            [
                'attribute' => 'executorsList',
                'label' => 'Executors',
                'format' => 'raw',
                'value' =>function (\app\models\Accident $data) {
                    if (!empty($data->works)) {
                        $executorsList = '';
                        $iter = 0;
                        foreach ($data->executorUser as $engineer) {
                            if ($iter != 0) $executorsList .= '<br>';
                            else $iter++;
//                        print_r($engineer->name);die();

                            $executorsList .= Html::a($engineer->name,
                                Url::to(['engineers/view', 'id' => $engineer->id]),
                                [
                                    'title' => $engineer->name,
                                    'target' => '_blank'
                                ]
                            );
                        }
                        return $executorsList;
                    } else return '-';
                },
            ],
            [
                'attribute' => 'accountantUser_username',
                'label' => 'Accountant',
                'format' => 'raw',
                'value' =>function (\app\models\Accident $data) {
                    if (!empty($data->accountantUser->username)) {
                        return $data->accountantUser->username;
                    }
                },
            ],
            [
                'attribute' => 'works_works',
                'label' => 'Работы',
                'format' => 'raw',
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
                    if((!empty($data->stores->city_id))&&($data->acc_status!='CARRIED_OUT')) $return_works.='<a class="btn btn-success" href="/works/create?accident_id='.$data->id.'&city_id='.$data->stores->city_id.'">Создать работу</a>';
                    return $return_works;
                },
            ],
            'fault',
            [
                'attribute' => 'own_equip_sum',
                'label' => 'Сумма собств.оборуд.',
                'format' => 'html',
                'value' =>function (\app\models\Accident $data) {
                    $sum = 0;
                    foreach ($data->works as $work) {
                        if (!empty($work->own_equip_sum)) $sum+=$work->own_equip_sum;
                    }
                    return $sum;
                },
            ],
            [
                'attribute' => 'hourSum',
                'label' => 'Amount of hours',
                'format' => 'html',
                'value' =>function (\app\models\Accident $data) {
                    $full_hours = Works::getHoursAccidentWorkByAccidentModel($data);
                    if ($full_hours===0) return '-';
                    elseif ($full_hours===false) return 'в работе';
                    else return $full_hours;
                },
            ],
            [
                'attribute' => 'actFile',
                'label' => 'File of act',
                'format' => 'raw',
                'value' =>function (\app\models\Accident $data) {
                    $files = Files::find()->where(['link_id' => $data->id, 'table_name' => 'accident', 'type' => 'act_pdf'])->all();
                    $returned_files = '';
                    if (!empty($files)) foreach ($files as $file) $returned_files .= Html::a('<span class="glyphicon glyphicon-paperclip"></span>',
                        Url::to(['files/view', 'id'=>$file->id]),
                        [
                            'title' => $file->name,
                            'target' => '_blank'
                        ]
                    );
                    return $returned_files;
                },
            ],
            [
                'attribute' => 'Summary',
                'label' => 'Summary',
                'format' => 'html',
                'value' =>function (\app\models\Accident $data) {
                    $full_price = Works::getCalculatedAccidentWorkByAccidentModel($data);
                    if ($full_price===0) return '-';
                    elseif ($full_price===false) return 'в работе';
                    else return $full_price;
                },
            ],
            [
                'attribute' => 'mail_string',
                'label' => 'Строка',
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

                    if ($flag) return '<div class="copy_block hidden_text"><span>'.$return_data.'</span><a class="glyphicon glyphicon-duplicate" title="Копировать"></a>';
                    else return '';
                },
            ],
            [
                'attribute' => 'buttons',
                'label' => 'Кнопки',
                'format' => 'raw',
                'value' =>function (\app\models\Accident $data) {
                    $return = '<form class="add_message buttons_form" action="/logs/create-message"><input type="hidden" name="accident_id" value="'.$data->id.'">';
                    $return .= Html::button('Отменить статус', ['class' => 'cancel_status btn btn-primary js-cause-send', 'data' => ['id' => $data->id, 'type' => 'accident']]);
                    $return .= Html::button('Ошибочный инцидент', ['class' => 'error_status btn btn-danger js-cause-send', 'data' => ['id' => $data->id, 'type' => 'accident']]);
                    $return .= '</form>';
                    return $return;
                },
            ]
        ];

        /*    if ($user_group<=2) {
                $columns[]=[
                    'label' => 'Сумма часов',
                    'value' =>function (\app\models\Accident $data) {
                        $sum_time=0;
                        foreach($data->works as $work) {
                            $sum_time+=$work->act_time_hour;
                        }
                        return $sum_time;
                    },
                ];
                $columns[]=[
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
                $columns[]=['class' => 'yii\grid\ActionColumn',
                    'template' => '{view}<br>{update}<br>{delete}<br>{status}<br>{calculate}',
                    'buttons' => [
                        'status' => function($model, $key, $index) {
                            $flag=1;
                            foreach ($key->works as $work) if ($work->status!='ОПЛАЧЕН') $flag=0;
        //return print_r($key->works,true);
                            if(empty($key->acc_status)) $key->acc_status='OPEN';
                            if(($flag==1)||($key->acc_status!='OPEN')) {
                                if ($key->acc_status=='OPEN') return Html::a(
                                    '<span class="glyphicon glyphicon-ok"></span>',
                                    Url::to(['accident/set-status', 'id'=>$key->id, 'status'=>'CLOSED']),
                                    [
                                        'title' => 'Закрыть инцидент',
                                        'class' => 'change_status'
                                    ]).'<br>'.Html::a(
                                        '<span class="glyphicon glyphicon-remove-circle"></span>',
                                        Url::to(['accident/set-status', 'id' => $key->id, 'status'=>'CANCELED']),
                                        [
                                            'title' => 'Отменить инцидент',
                                            'class' => 'change_status'
                                        ]);
                                elseif ($key->acc_status == 'CANCELED') return Html::a(
                                    '<span class="glyphicon glyphicon-share-alt"></span>',
                                    Url::to(['accident/set-status', 'id'=>$key->id, 'status'=>'OPEN']),
                                    [
                                        'title' => 'Открыть инцидент',
                                        'class' => 'change_status'
                                    ]);
                                elseif ($key->acc_status=='CARRIED_OUT')  return Html::a(
                                    '<span class="glyphicon glyphicon-thumbs-down"></span>',
                                    Url::to(['accident/set-status', 'id'=>$key->id, 'status'=>'CLOSED']),
                                    [
                                        'title' => 'Отменить проведение',
                                        'class' => 'change_status'
                                    ]);
                                else return Html::a(
                                    '<span class="glyphicon glyphicon-share-alt"></span>',
                                    Url::to(['accident/set-status', 'id'=>$key->id, 'status'=>'OPEN']),
                                    [
                                        'title' => 'Открыть инцидент',
                                        'class' => 'change_status'
                                    ]).'<br>'.Html::a(
                                    '<span class="glyphicon glyphicon-thumbs-up"></span>',
                                    Url::to(['accident/set-status', 'id'=>$key->id, 'status'=>'CARRIED_OUT']),
                                    [
                                        'title' => 'Провести',
                                        'class' => 'change_status'
                                    ]);
                            } else {
                                return '';
                            }
                        },
                        'calculate' => function($model, $key, $index) {

                            if (count($key->works)>0) {
                                $flag = 1;
                                foreach ($key->works as $work) {
                                    if (($work->status != 'ОПЛАЧЕН') && ($work->status != 'ЗАВЕРШЕН')) $flag = 0;
                                }

                                if ($flag == 1) {
                                    return Html::a(
                                        '<span class="glyphicon glyphicon-euro"></span>',
                                        Url::to(['works/calculate-all-work', 'id' => $key->id]),
                                        [
                                            'title' => 'Пересчитать работы',
                                            'class' => 'calculate_work'
                                        ]);
                                }
                            }
                        }
                    ]
                ];
            } else {
                $columns[] = ['class' => 'yii\grid\ActionColumn',
                    'template' => '{view}{update}{delete}{status}',
                    'buttons' => [
                        'status' => function ($model, $key, $index) {
                            $flag = 1;
                            foreach ($key->works as $work) if ($work->status != 'ОПЛАЧЕН') $flag = 0;
        //return print_r($key->works,true);
                            if (empty($key->acc_status)) $key->acc_status = 'OPEN';
                            if ((($flag == 1) || ($key->acc_status != 'OPEN'))&&($key->acc_status != 'CARRIED_OUT')) {
                                if ($key->acc_status == 'OPEN') return Html::a(
                                    '<span class="glyphicon glyphicon-ok"></span>',
                                    Url::to(['accident/set-status', 'id' => $key->id, 'status'=>'CLOSED']),
                                    [
                                        'title' => 'Закрыть инцидент',
                                        'class' => 'change_status'
                                    ]).'<br>'.Html::a(
                                        '<span class="glyphicon glyphicon-remove-circle"></span>',
                                        Url::to(['accident/set-status', 'id' => $key->id, 'status'=>'CANCELED']),
                                        [
                                            'title' => 'Отменить инцидент',
                                            'class' => 'change_status'
                                        ]);
                                elseif ($key->acc_status == 'CANCELED') return Html::a(
                                    '<span class="glyphicon glyphicon-share-alt"></span>',
                                    Url::to(['accident/set-status', 'id'=>$key->id, 'status'=>'OPEN']),
                                    [
                                        'title' => 'Открыть инцидент',
                                        'class' => 'change_status'
                                    ]);
                                elseif ($key->acc_status=='CARRIED_OUT')  return '';
                                else return Html::a(
                                    '<span class="glyphicon glyphicon-share-alt"></span>',
                                    Url::to(['accident/set-status', 'id' => $key->id, 'status'=>'OPEN']),
                                    [
                                        'title' => 'Открыть инцидент',
                                        'class' => 'change_status'
                                    ]);
                            } else {
                                return '';
                            }
                        }
                    ]
                ];
            }*/
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $columns,
            'tableOptions' => [
                'class' => 'table table-striped table-bordered',
                'id' => 'accident'
            ],
        ]); ?>
    </div>