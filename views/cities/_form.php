<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cities */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cities-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?/*if ($user_id==101) {?>

    <?= $form->field($model, 'rate_work')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rate_hol')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rate_depart_work')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rate_depart_hol')->textInput(['maxlength' => true]) ?>

    <?}*/?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
