<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Cities */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'City', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить город?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'name',
            [
                'attribute' => 'stores.store_name',
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
                'attribute' => 'engineers.name',
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
        ],
    ]) ?>

</div>
