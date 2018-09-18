<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cities */

$this->title = 'Редактирование тарифов городов';
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="cities-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_admin', [
        'model' => $model,
        'user' => $user,
        'krasnodar' => $krasnodar,
    ]) ?>

</div>
