<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StoresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stores-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?if (!empty($shown_cols)) {
    $decoded_shown_cols = json_decode($shown_cols);
    if (!empty($decoded_shown_cols->stores)) {
    $index_shown_cols = explode(',', $decoded_shown_cols->stores);?>
    <div id="db_cols" class="<?foreach ($index_shown_cols as $index_shown_col) echo "show".$index_shown_col.' ';?>">
        <?}
        }?>
    <p>
        <?= Html::a('Добавить магазин', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'inner_id',
            [
                'attribute' => 'cities_name',
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
            'store_name',
            'address',
            [
                'attribute' => 'equipment_equip',
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
                'attribute' => 'accident_acc_id',
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
            // 'tel1',
            // 'dept1',
            // 'note1',
            // 'tel2',
            // 'dept2',
            // 'note2',
            // 'tel3',
            // 'dept3',
            // 'note3',
            // 'tel4',
            // 'dept4',
            // 'note4',
            // 'tel5',
            // 'dept5',
            // 'note5',
            // 'end_of_work',
            // 'pics',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
            'id' => 'stores'
        ],
    ]); ?>
</div>
