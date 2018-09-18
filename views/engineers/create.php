<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Engineers */

$this->title = 'Создание инженера';
$this->params['breadcrumbs'][] = ['label' => 'Инженеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="engineers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'getParams' => $getParams,
    ]) ?>

</div>
