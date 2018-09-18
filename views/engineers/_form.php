<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use app\models\Cities;

/* @var $this yii\web\View */
/* @var $model app\models\Engineers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="engineers-form">

    <?if (!empty($getParams['city_id'])) $city_options=array($getParams['city_id']=>array('Selected'=>true)); else $city_options=array();?>
    <?if (!empty($getParams['payment_target'])) $payment_target=array($getParams['payment_target']=>array('Selected'=>true)); else $payment_target=array();?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?//= $form->field($model, 'city_id')->textInput() ?>
    <?= $form->field($model, 'city_id')->dropDownList(ArrayHelper::map(Cities::find()->all(), 'id', 'name'), array('prompt' => 'Укажите город', 'options' => $city_options)) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel1')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => ['+9(999)999-99-99', '+99(999)999-99-99']
    ]) ?>

    <?= $form->field($model, 'tel2')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => ['+9(999)999-99-99', '+99(999)999-99-99'],
    ]) ?>

    <?//= $form->field($model, 'tel1')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'tel2')->textInput(['maxlength' => true])?>

    <?= $form->field($model, 'email1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tariff')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_target')->dropDownList(array('card'=>'На карту','cardPhone'=>'На карту, привязанную к телефону','wallet'=>'На кошелёк','walletEl'=>'На электронный кошелёк'), array('prompt' => 'Укажите назначение оплаты', 'options' => $payment_target)) ?>

    <?= $form->field($model, 'payment')->textInput() ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <div class="form-group field-files-url">
        <label class="control-label" for="files-url">Паспорт</label>
        <div class="pics">
            <?php foreach ($model->files as $pics){
                if ($pics->type=='passport') {?>
                    <div class="pic">
                        <?echo Html::a(Html::img($pics->url, $options = ['class' => 'postImg', 'style' => ['width' => '180px']]), [$pics->url], ['class' => 'link-download', 'target' => '_blank']);?>
                        <?echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['#'], ['class' => 'link-del', 'data-href' => '/files/delete-image', 'data-id' => $pics->id]);?>
                    </div>
                <?}
            }?>
        </div>
        <?
        echo FileInput::widget([
            'name' => 'passport[]',
            'options'=>[
                'multiple'=>true,
                'accept' => 'image/*'
            ],
            'pluginOptions' => ['previewFileType' => 'image']
        ]);
        ?>
        <!--<input type="hidden" name="passport[]" value=""><input type="file" id="files-url" name="passport[]" multiple="" accept="image/*">-->
<!--        <div class="inputfile"><label><span>Прикрепить файл</span><input type="file" id="files-url" name="passport[]" multiple="" accept="image/*"></label></div>-->
        <div class="help-block"></div>
    </div>

    <div class="form-group field-files-url">
        <label class="control-label" for="files-url">Фото</label>
        <div class="pics">
            <?php foreach ($model->files as $pics){
                if ($pics->type=='photo') {?>
                    <div class="pic">
                        <?echo Html::a(Html::img($pics->url, $options = ['class' => 'postImg', 'style' => ['width' => '180px']]), [$pics->url], ['class' => 'link-download', 'target' => '_blank']);?>
                        <?echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['#'], ['class' => 'link-del', 'data-href' => '/files/delete-image', 'data-id' => $pics->id]);?>
                    </div>
                <?}
            }?>
        </div>
        <?
        echo FileInput::widget([
            'name' => 'photo[]',
            'options'=>[
                'multiple'=>true,
                'accept' => 'image/*'
            ],
            'pluginOptions' => ['previewFileType' => 'image']
        ]);
        ?>
        <!--<input type="hidden" name="photo[]" value=""><input type="file" id="files-url" name="photo[]" multiple="" accept="image/*">-->
        <!--<div class="inputfile"><label><span>Прикрепить файл</span><input type="file" id="files-url" name="photo[]" multiple="" accept="image/*"></label></div>-->
        <div class="help-block"></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
