<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Accident */

$this->title = 'Редактировние инцидента: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Инциденты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="accident-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
