<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AccidentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="accident-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'acc_id') ?>

    <?= $form->field($model, 'act_number') ?>

    <?= $form->field($model, 'act_date') ?>

    <?= $form->field($model, 'priority') ?>

    <?php // echo $form->field($model, 'store_id') ?>

    <?php // echo $form->field($model, 'equipment_id') ?>

    <?php // echo $form->field($model, 'fault') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
