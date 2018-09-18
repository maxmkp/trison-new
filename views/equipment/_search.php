<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EquipmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="equipment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'equip') ?>

    <?= $form->field($model, 'pc_name') ?>

    <?= $form->field($model, 'player_id') ?>

    <?= $form->field($model, 'net_cable_name') ?>

    <?php // echo $form->field($model, 'patch_port') ?>

    <?php // echo $form->field($model, 'switch_port') ?>

    <?php // echo $form->field($model, 'led_screen_fuse') ?>

    <?php // echo $form->field($model, 'pc_fuse') ?>

    <?php // echo $form->field($model, 'store_id') ?>

    <?php // echo $form->field($model, 'note') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
