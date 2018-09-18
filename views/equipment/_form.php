<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use app\models\Stores;
use app\models\Cities;

/* @var $this yii\web\View */
/* @var $model app\models\Equipment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="equipment-form">

    <?if (!empty($model->store_id)) $getParams['store_id']=$model->store_id;?>
    <?if (!empty($getParams['store_id'])) $store_options=array($getParams['store_id']=>array('Selected'=>true)); else $store_options=array();?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'equip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pc_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'player_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'net_cable_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patch_port')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'switch_port')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'led_screen_fuse')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pc_fuse')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'store_id')->textInput() ?>

    <?if (!empty($getParams['store_id'])) {?>

        <?= $form->field($model, 'store_id')->hiddenInput(['value' => $getParams['store_id']]) ?>
        <?= Html::input('text', 'store_id_show', Stores::find()->where(['id'=>$getParams['store_id']])->one()->store_name, ['readonly' => true, 'class' => 'form-control', 'style' => 'margin-bottom:15px;margin-top:-15px;']) ?>

    <?} else {?>

        <div class="form-group">

            <label class="control-label">City</label>

            <?= Html::dropDownList('city_id_show', 0, ArrayHelper::map(Cities::find()->all(), 'id', 'name'), ['prompt' => 'Укажите город', 'id' => 'city_id_show', 'class' => 'form-control ajax', 'data-href' => '/equipment/get-stores']) ?>

        </div>

        <?= $form->field($model, 'store_id')->dropDownList(array(), array('prompt' => 'Укажите магазин', 'class' => 'after_city_id_show form-control', 'disabled' => true)) ?>

    <?}?>

<!--    --><?//= $form->field($model, 'store_id')->dropDownList(ArrayHelper::map(Stores::find()->all(), 'id', 'store_name'), array('prompt' => 'Укажите магазин', 'options' => $store_options)) ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
