<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use app\models\Cities;
use app\models\Files;

/* @var $this yii\web\View */
/* @var $model app\models\Stores */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stores-form">

    <?if (!empty($getParams['city_id'])) $city_options=array($getParams['city_id']=>array('Selected'=>true)); else $city_options=array();?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'inner_id')->textInput() ?>

    <?//= $form->field($model, 'city_id')->textInput() ?>

    <?= $form->field($model, 'city_id')->dropDownList(ArrayHelper::map(Cities::find()->all(), 'id', 'name'), array('prompt' => 'Укажите город', 'options' => $city_options)) ?>

    <?= $form->field($model, 'store_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dept1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dept2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dept3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dept4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tel5')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dept5')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note5')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'end_of_work')->textInput(['maxlength' => true]) ?>
    <div class="form-group field-files-url">
        <label class="control-label" for="files-url">End of work</label>
        <div class="pics">
            <?php foreach ($model->files as $pics){
                if ($pics->type=='end_of_work') {?>
                    <div class="pdf">
                        <?echo Html::a($pics->name, [$pics->url], ['class' => 'link-download', 'target' => '_blank']);?>
                        <?echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['#'], ['class' => 'link-del', 'data-href' => '/files/delete-image', 'data-id' => $pics->id]);?>
                    </div>
                <?}
            }?>
        </div>
        <?
        echo FileInput::widget([
            'name' => 'end_of_work[]',
            'options'=>[
                'multiple'=>true,
                'accept' => 'image/*'
            ],
            'pluginOptions' => ['previewFileType' => 'image']
        ]);
        ?>
        <!--<input type="hidden" name="end_of_work[]" value=""><input type="file" id="files-url" name="end_of_work[]" multiple="" accept="image/*">-->
<!--        <div class="inputfile"><label><span>Прикрепить файл</span><input type="file" id="files-url" name="end_of_work[]" multiple="" accept="image/*"></label></div>-->
        <div class="help-block"></div>
    </div>

    <?//= $form->field($model, 'pics')->textInput(['maxlength' => true]) ?>
    <div class="form-group field-files-url">
        <label class="control-label" for="files-url">Изображения</label>
        <div class="pics">
            <?php foreach ($model->files as $pics){
                if ($pics->type=='pics') {?>
                    <div class="pic">
                        <?echo Html::a(Html::img($pics->url, $options = ['class' => 'postImg', 'style' => ['width' => '180px']]), [$pics->url], ['class' => 'link-download', 'target' => '_blank']);?>
                        <?echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['#'], ['class' => 'link-del', 'data-href' => '/files/delete-image', 'data-id' => $pics->id]);?>
                    </div>
                <?}
            }?>
        </div>
        <?
        echo FileInput::widget([
            'name' => 'pics[]',
            'options'=>[
                'multiple'=>true,
                'accept' => 'image/*'
            ],
            'pluginOptions' => ['previewFileType' => 'image']
        ]);
        ?>
        <!--<input type="hidden" name="pics[]" value=""><input type="file" id="files-url" name="pics[]" multiple="" accept="image/*">-->
<!--        <div class="inputfile"><label><span>Прикрепить файл</span><input type="file" id="files-url" name="pics[]" multiple="" accept="image/*"></label></div>-->
        <div class="help-block"></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
