<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Equipment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Equipment', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equipment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить оборудование?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'equip',
            'pc_name',
            'player_id',
            'net_cable_name',
            'patch_port',
            'switch_port',
            'led_screen_fuse',
            'pc_fuse',
            [
                'attribute' => 'stores.store_name',
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
                'attribute' => 'cities.name',
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
        ],
    ]) ?>

</div>
