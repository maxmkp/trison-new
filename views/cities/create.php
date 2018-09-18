<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Cities */

$this->title = 'Создание города';
$this->params['breadcrumbs'][] = ['label' => 'Города', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cities-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'user_id' => $user_id,
    ]) ?>

</div>
