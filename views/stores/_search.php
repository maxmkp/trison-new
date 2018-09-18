<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StoresSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stores-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'inner_id') ?>

    <?= $form->field($model, 'city_id') ?>

    <?= $form->field($model, 'store_name') ?>

    <?= $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'tel1') ?>

    <?php // echo $form->field($model, 'dept1') ?>

    <?php // echo $form->field($model, 'note1') ?>

    <?php // echo $form->field($model, 'tel2') ?>

    <?php // echo $form->field($model, 'dept2') ?>

    <?php // echo $form->field($model, 'note2') ?>

    <?php // echo $form->field($model, 'tel3') ?>

    <?php // echo $form->field($model, 'dept3') ?>

    <?php // echo $form->field($model, 'note3') ?>

    <?php // echo $form->field($model, 'tel4') ?>

    <?php // echo $form->field($model, 'dept4') ?>

    <?php // echo $form->field($model, 'note4') ?>

    <?php // echo $form->field($model, 'tel5') ?>

    <?php // echo $form->field($model, 'dept5') ?>

    <?php // echo $form->field($model, 'note5') ?>

    <?php // echo $form->field($model, 'end_of_work') ?>

    <?php // echo $form->field($model, 'pics') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
