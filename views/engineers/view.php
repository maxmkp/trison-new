<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Engineers */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Engineer', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="engineers-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить инженера?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            [
                'attribute' => 'cities.name',
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
                'attribute' => 'works.works',
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
            [
                'attribute' => 'files.url',
                'label' => 'Паспорт',
                'format' => 'raw',
                'value' =>function (\app\models\Engineers $data) {
                    $return_pics='';
                    foreach ($data->files as $pic) {
                        if ($pic->type=='passport') $return_pics.='<div class="pic">'.Html::a(Html::img($pic->url, $options = ['class' => 'postImg', 'style' => ['width' => '360px']]), Url::to(['files/view', 'id'=>$pic->id]), ['class' => 'link-download', 'target' => '_blank']).'</div><br>';
                    }
                    return $return_pics;
                },
            ],
            [
                'attribute' => 'files.url',
                'label' => 'Фото',
                'format' => 'raw',
                'value' =>function (\app\models\Engineers $data) {
                    $return_pics='';
                    foreach ($data->files as $pic) {
                        if ($pic->type=='photo') $return_pics.='<div class="pic">'.Html::a(Html::img($pic->url, $options = ['class' => 'postImg', 'style' => ['width' => '360px']]), Url::to(['files/view', 'id'=>$pic->id]), ['class' => 'link-download', 'target' => '_blank']).'</div><br>';
                    }
                    return $return_pics;
                },
            ],
        ],
    ]) ?>

</div>
