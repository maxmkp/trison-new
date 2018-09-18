<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Stores */

$this->title = 'Создание магазина';
$this->params['breadcrumbs'][] = ['label' => 'Магазины', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stores-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'getParams' => $getParams,
    ]) ?>

</div>
