<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Works */

$this->title = $model->note;
$this->params['breadcrumbs'][] = ['label' => 'Work', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('/css/im.css');
?>
<div class="works-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить работу?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?$attributes1=[
//            'id',
//            'accident_id',
        [
            'attribute' => 'accident.acc_id',
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
        [
            'attribute' => 'start_date',
            'value' =>function (\app\models\Works $data) {
                if (!empty($data->start_date)) return date('d.m.Y', strtotime($data->start_date));
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
            'attribute' => 'pause_date',
            'value' =>function (\app\models\Works $data) {
                if (!empty($data->pause_date)) return date('d.m.Y', strtotime($data->pause_date));
                else return '';
            },
        ],
        'reason:ntext',
        [
            'attribute' => 'end_pause_date',
            'value' =>function (\app\models\Works $data) {
                if (!empty($data->end_pause_date)) return date('d.m.Y', strtotime($data->end_pause_date));
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
        [
            'attribute' => 'engineers.name',
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
        'status',
//            'payment',
        [
            'attribute' => 'payment',
            'value' =>function (\app\models\Works $data) {
                if (!empty($data->payment)) return 'Yes';
                else return 'No';
            },
        ],
        'summary',
        'own_equip_sum',
    ];

    /*if ($user_id==101) {
        $attributes1[]='rate';
        $attributes1[]=[
            'attribute' => 'full_price',
            'format' => 'html',
            'value' =>function (\app\models\Works $data) {
                if (!empty($data->full_price)) $return_full_price=$data->full_price.' руб.    ';
                else $return_full_price='';

                if(($data->status=='ОПЛАЧЕН')||($data->status=='ЗАВЕРШЕН')) {
                    return $return_full_price.Html::a(
                        '<span class="glyphicon glyphicon-euro">ПЕРЕСЧИТАТЬ</span>',
                        Url::to(['works/calculate-work', 'id'=>$data->id]),
                        [
                            'title' => 'Посчитать на дату закрытия',
                            'class' => 'calculate_work'
                        ]);
                }
            },
        ];
    }*/
    $attributes2=[
        'note:ntext',
        [
            'attribute' => 'files.url',
            'label' => 'PDF',
            'format' => 'raw',
            'value' =>function (\app\models\Works $data) {
                $return_pics='';
                foreach ($data->files as $pic) {
                    if ($pic->type=='act_pdf') $return_pics.='<div class="pdf">'.Html::a($pic->name, Url::to(['files/view', 'id'=>$pic->id]), ['class' => 'link-download', 'target' => '_blank']).'</div><br>';
                }
                return $return_pics;
            },
        ],
        [
            'attribute' => 'files.url',
            'label' => 'Сканы',
            'format' => 'raw',
            'value' =>function (\app\models\Works $data) {
                $return_pics='';
                foreach ($data->files as $pic) {
                    if ($pic->type=='act_scan') $return_pics.='<div class="pic">'.Html::a(Html::img($pic->url, $options = ['class' => 'postImg', 'style' => ['width' => '360px']]), Url::to(['files/view', 'id'=>$pic->id]), ['class' => 'link-download', 'target' => '_blank']).'</div><br>';
                }
                return $return_pics;
            },
        ],
        [
            'attribute' => 'files.url',
            'label' => 'Изображения',
            'format' => 'raw',
            'value' =>function (\app\models\Works $data) {
                $return_pics='';
                foreach ($data->files as $pic) {
                    if ($pic->type=='pics') $return_pics.='<div class="pic">'.Html::a(Html::img($pic->url, $options = ['class' => 'postImg', 'style' => ['width' => '360px']]), Url::to(['files/view', 'id'=>$pic->id]), ['class' => 'link-download', 'target' => '_blank']).'</div><br>';
                }
                return $return_pics;
            },
        ],
//            'act_pdf',
//            'act_scan',
//            'pics',
    ];

    $attributes2[]=[
        'label' => 'Логи',
        'format' => 'raw',
        'value' =>function (\app\models\Works $data) {
            $options = ['class' => 'logs_table', 'data' => ['acc' => $data->accident_id, 'work' => $data->id]];
            $res = Html::tag('div', '', $options);

            $res .= '<form class="add_message" action="/logs/create-message">
                <input type="hidden" name="work_id" value="<?=$data->id?>">
                <input type="hidden" name="accident_id" value="'.$data->accident_id.'">
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
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => array_merge($attributes1, $attributes2)
    ]) ?>
</div>
