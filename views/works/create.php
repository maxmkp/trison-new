<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Works */

$this->title = 'Создание работ';
$this->params['breadcrumbs'][] = ['label' => 'Работы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="works-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
//        'modelPics' => $modelPics,
        'getParams' => $getParams,
    ]) ?>

</div>
