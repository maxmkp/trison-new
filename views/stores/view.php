<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Stores */

$this->title = $model->store_name;
$this->params['breadcrumbs'][] = ['label' => 'Store', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stores-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить магазин?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'inner_id',
            [
                'attribute' => 'cities.name',
                'label' => 'City',
                'format' => 'raw',
                'value' =>function (\app\models\Stores $data) {
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
            [
                'attribute' => 'equipment.equip',
                'label' => 'Equipment',
                'format' => 'raw',
                'value' =>function (\app\models\Stores $data) {
                    $return_equipment='';
                    foreach ($data->equipment as $equip) {
                        $return_equipment.=Html::a(
                                $equip->equip,
                                Url::to(['equipment/view', 'id'=>$equip->id]),
                                [
                                    'title' => $equip->equip,
                                    'target' => '_blank'
                                ]
                            ).'<br><hr>';
                    }
                    $return_equipment.='<a class="btn btn-success" href="/equipment/create?store_id='.$data->id.'">Создать оборудование</a>';
                    return $return_equipment;
                },
            ],
            [
                'attribute' => 'accident.acc_id',
                'label' => 'Accidents',
                'format' => 'raw',
                'value' =>function (\app\models\Stores $data) {
                    $return_accident='';
                    foreach ($data->accident as $acc) {
                        $return_accident.=Html::a(
                                $acc->acc_id,
                                Url::to(['accident/view', 'id'=>$acc->id]),
                                [
                                    'title' => $acc->acc_id,
                                    'target' => '_blank'
                                ]
                            ).'<br><hr>';
                    }
                    $return_accident.='<a class="btn btn-success" href="/accident/create?store_id='.$data->id.'">Создать инцидент</a>';
                    return $return_accident;
                },
            ],
            'store_name',
            'address',
            'tel1',
            'dept1',
            'note1',
            'tel2',
            'dept2',
            'note2',
            'tel3',
            'dept3',
            'note3',
            'tel4',
            'dept4',
            'note4',
            'tel5',
            'dept5',
            'note5',
//            'end_of_work',
            [
                'attribute' => 'files.url',
                'label' => 'End of work',
                'format' => 'raw',
                'value' =>function (\app\models\Stores $data) {
                    $return_pics='';
                    foreach ($data->files as $pic) {
                        if ($pic->type=='end_of_work') $return_pics.='<div class="pdf">'.Html::a($pic->name, Url::to(['files/view', 'id'=>$pic->id]), ['target' => '_blank', 'class' => 'link-download']).'</div><br>';
                    }
                    return $return_pics;
                },
            ],
            [
                'attribute' => 'files.url',
                'label' => 'Изображения',
                'format' => 'raw',
                'value' =>function (\app\models\Stores $data) {
                    $return_pics='';
                    foreach ($data->files as $pic) {
                        if ($pic->type=='pics') $return_pics.='<div class="pic">'.Html::a(Html::img($pic->url, $options = ['class' => 'postImg', 'style' => ['width' => '360px']]), Url::to(['files/view', 'id'=>$pic->id]), ['class' => 'link-download', 'target' => '_blank']).'</div><br>';
                    }
                    return $return_pics;
                },
            ],
        ],
    ]) ?>

</div>
