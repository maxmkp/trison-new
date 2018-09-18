<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EquipmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Equipment';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?if (!empty($shown_cols)) {
    $decoded_shown_cols = json_decode($shown_cols);
    if (!empty($decoded_shown_cols->equipment)) {
    $index_shown_cols = explode(',', $decoded_shown_cols->equipment);?>
    <div id="db_cols" class="<?foreach ($index_shown_cols as $index_shown_col) echo "show".$index_shown_col.' ';?>">
        <?}
        }?>
    <p>
        <?= Html::a('Добавить оборудование', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'equip',
//            'pc_name',
//            'player_id',
//            'net_cable_name',
            // 'patch_port',
            // 'switch_port',
            // 'led_screen_fuse',
            // 'pc_fuse',
            [
                'attribute' => 'stores_store_name',
                'label' => 'Store',
                'format' => 'raw',
                'value' =>function (\app\models\Equipment $data) {
                    if (!empty($data->stores->store_name)) {
                        return Html::a(
                            $data->stores->store_name,
                            Url::to(['stores/view', 'id' => $data->stores->id]),
                            [
                                'title' => $data->stores->store_name,
                                'target' => '_blank'
                            ]
                        );
                    } else return '';
                },
            ],
            [
                'attribute' => 'cities_name',
                'label' => 'City',
                'format' => 'raw',
                'value' =>function (\app\models\Equipment $data) {
                    if (!empty($data->cities->name)) {
                        return Html::a(
                            $data->cities->name,
                            Url::to(['cities/view', 'id' => $data->cities->id]),
                            [
                                'title' => $data->cities->name,
                                'target' => '_blank'
                            ]
                        );
                    } else return '';
                },
            ],
             'note:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
            'id' => 'equipment'
        ],
    ]); ?>
</div>
