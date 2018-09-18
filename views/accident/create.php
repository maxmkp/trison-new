<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Accident */

$this->title = 'Создание инцидента';
$this->params['breadcrumbs'][] = ['label' => 'Инциденты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accident-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'getParams' => $getParams,
    ]) ?>

</div>
