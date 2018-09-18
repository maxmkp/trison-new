<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WorksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="works-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'accident_id') ?>

    <?= $form->field($model, 'start_date') ?>

    <?= $form->field($model, 'completion_date') ?>

    <?= $form->field($model, 'pause_date') ?>

    <?php // echo $form->field($model, 'reason') ?>

    <?php // echo $form->field($model, 'end_pause_date') ?>

    <?php // echo $form->field($model, 'act_time') ?>

    <?php // echo $form->field($model, 'real_time') ?>

    <?php // echo $form->field($model, 'full_work_performed') ?>

    <?php // echo $form->field($model, 'engineer_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'payment') ?>

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'act_pdf') ?>

    <?php // echo $form->field($model, 'act_scan') ?>

    <?php // echo $form->field($model, 'pics') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
