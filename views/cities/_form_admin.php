<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cities */
/* @var $form yii\widgets\ActiveForm */
?>
<?if (($user->user_group==20)||($user->user_group==1)) {?>
<div class="cities-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
    <?= Html::label('Тариф('.$krasnodar->name.')') ?>
    <?= Html::input('text', 'krasnodar_rate_work', $krasnodar->rate_work, ['class' => 'form-control']) ?>
    </div>

    <div class="form-group">
    <?= Html::label('Тариф('.$krasnodar->name.', вых)') ?>
    <?= Html::input('text', 'krasnodar_rate_hol', $krasnodar->rate_hol, ['class' => 'form-control']) ?>
    </div>

    <div class="form-group">
    <?= Html::label('Тариф('.$krasnodar->name.', выезд)') ?>
    <?= Html::input('text', 'krasnodar_rate_depart_work', $krasnodar->rate_depart_work, ['class' => 'form-control']) ?>
    </div>

    <div class="form-group">
    <?= Html::label('Тариф('.$krasnodar->name.', выезд,вых)') ?>
    <?= Html::input('text', 'krasnodar_rate_depart_hol', $krasnodar->rate_depart_hol, ['class' => 'form-control']) ?>
    </div>

    <?= $form->field($model, 'rate_work')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rate_hol')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rate_depart_work')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rate_depart_hol')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?}?>