<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CitiesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?if (!empty($shown_cols)) {
        $decoded_shown_cols = json_decode($shown_cols);
        if (!empty($decoded_shown_cols->cities)) {
        $index_shown_cols = explode(',', $decoded_shown_cols->cities);?>
            <div id="db_cols" class="<?foreach ($index_shown_cols as $index_shown_col) echo "show".$index_shown_col.' ';?>">
        <?}
    }?>

    <p>
        <?= Html::a('Добавить город', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?$columns=[
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
        //'name',
        [
            'attribute' => 'name',
            'value' => 'name',
        ],
        [
            'attribute' => 'stores_store_name',
            'label' => 'Stores',
            'format' => 'raw',
            'value' =>function (\app\models\Cities $data) {
                $return_stores='';
                foreach ($data->stores as $store) {
                    $return_stores.=Html::a(
                            $store->store_name,
                            Url::to(['stores/view', 'id'=>$store->id]),
                            [
                                'title' => $store->store_name,
                                'target' => '_blank'
                            ]
                        ).'<br><hr>';
                }
                $return_stores.='<a class="btn btn-success" href="/stores/create?city_id='.$data->id.'">Создать магазин</a>';
                return $return_stores;
            },
        ],
        [
            'attribute' => 'countries_name',
            'label' => 'Country',
            'format' => 'raw',
            'value' =>function (\app\models\Cities $data) {
                if (!empty($data->countries->name)) {
                    return Html::a(
                        $data->countries->name,
                        Url::to(['countries/view', 'id' => $data->countries->id]),
                        [
                            'title' => $data->countries->name,
                            'target' => '_blank'
                        ]
                    );
                }
            },
        ],
        [
            'attribute' => 'engineers_name',
            'label' => 'Engineers',
            'format' => 'raw',
            'value' =>function (\app\models\Cities $data) {
                $return_engs='';
                foreach ($data->engineers as $eng) {
                    $return_engs.=Html::a(
                            $eng->name,
                            Url::to(['engineers/view', 'id'=>$eng->id]),
                            [
                                'title' => $eng->name,
                                'target' => '_blank'
                            ]
                        ).'<br><hr>';
                }
                $return_engs.='<a class="btn btn-success" href="/engineers/create?city_id='.$data->id.'">Создать инженера</a>';
                return $return_engs;
            },
        ],
    ];
    if (($user['user_group']==20)||($user['user_group']==1)) {
        $columns[]='rate_work';
        $columns[]='rate_hol';
        $columns[]='rate_depart_work';
        $columns[]='rate_depart_hol';
    }
    $columns[]=[
            'class' => 'yii\grid\ActionColumn'
    ];?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
            'id' => 'cities'
        ],
    ]); ?>
</div>
