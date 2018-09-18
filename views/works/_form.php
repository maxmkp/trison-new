<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use app\models\Accident;
use app\models\Engineers;
use app\models\Cities;
use app\models\Files;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Works */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="works-form">
    <?if (!empty($model->accident_id)) {
        $getParams['accident_id']=$model->accident_id;
//        $getParams['city_id']=$model->cities->id;
    }?>
    <?if (!empty($model->cities->id)) {
        $getParams['city_id']=$model->cities->id;
    }?>
    <?if (!empty($getParams['accident_id'])) $acc_options=array($getParams['accident_id']=>array('Selected'=>true)); else $acc_options=array();?>
    <?//if (!empty($getParams['engineer_id'])) $eng_options=array($getParams['engineer_id']=>array('Selected'=>true)); else $eng_options=array();?>
    <?if (!empty($getParams['city_id'])) $engineer_obj=Engineers::find()->where(['city_id' => $getParams['city_id']])->all(); else $engineer_obj=Engineers::find()->all();?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?//= $form->field($model, 'accident_id')->textInput() ?>
    <?/*if (!empty($getParams['accident_id'])) {?>

        <?= $form->field($model, 'accident_id')->hiddenInput(['value' => $getParams['accident_id']]) ?>
        <?= Html::input('text', 'accident_id_show', Accident::find()->where(['id'=>$getParams['accident_id']])->one()->acc_id, ['readonly' => true, 'class' => 'form-control', 'style' => 'margin-bottom:15px;margin-top:-15px;']) ?>

        <?= $form->field($model, 'worker')->dropDownList(ArrayHelper::map($engineer_obj, 'id', 'name'), array('prompt' => 'Укажите инженера', 'options' => $eng_options)) ?>
    <?} else {*/?>

        <?= $form->field($model, 'accident_id')->dropDownList(ArrayHelper::map(Accident::find()->all(), 'id', 'acc_id'), array('prompt' => 'Укажите инцидент', 'class' => 'form-control ajax', 'data-href' => '/works/get-engineers')) ?>

        <?= $form->field($model, 'worker')->dropDownList(ArrayHelper::map($engineer_obj, 'id', 'name'), array('prompt' => 'Укажите инженера', 'class' => 'form-control after_works-accident_id')) ?>

    <?//}?>

    <?if (!empty($model->start_date)) $model->start_date=date('d.m.Y', strtotime($model->start_date));?>
    <?= $form->field($model, 'start_date')->widget(DatePicker::classname(), [
//        'convertFormat'=>true,
        'language' => 'ru',
        'options' => [
            'placeholder' => '__.__.____',
        ],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd.mm.yyyy',
        ]
    ]);?>

    <?if (!empty($model->completion_date)) $model->completion_date=date('d.m.Y', strtotime($model->completion_date));?>
    <?= $form->field($model, 'completion_date')->widget(DatePicker::classname(), [
//        'convertFormat'=>true,
        'language' => 'ru',
        'options' => [
            'placeholder' => '__.__.____',
        ],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd.mm.yyyy',
        ]
    ]);?>

    <?if (!empty($model->pause_date)) $model->pause_date=date('d.m.Y', strtotime($model->pause_date));?>
    <?= $form->field($model, 'pause_date')->widget(DatePicker::classname(), [
//        'convertFormat'=>true,
        'language' => 'ru',
        'options' => [
            'placeholder' => '__.__.____',
        ],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd.mm.yyyy',
        ]
    ]);?>

    <?= $form->field($model, 'reason')->textarea(['rows' => 6]) ?>

    <?if (!empty($model->end_pause_date)) $model->end_pause_date=date('d.m.Y', strtotime($model->end_pause_date));?>
    <?= $form->field($model, 'end_pause_date')->widget(DatePicker::classname(), [
//        'convertFormat'=>true,
        'language' => 'ru',
        'options' => [
            'placeholder' => '__.__.____',
        ],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'dd.mm.yyyy',
        ]
    ]); ?>

    <?
    $hour_list=array();
    for ($hour = 0; $hour <= 24; $hour++) {
        $hour_list[$hour]=$hour.':00';
        if($hour!=24) $hour_list[$hour.'.5']=$hour.':30';
    }?>
    <?//= $form->field($model, 'act_time_hour')->dropDownList($hour_list) ?>

    <?= $form->field($model, 'act_time_hour_begin')->dropDownList($hour_list) ?>

    <?= $form->field($model, 'act_time_hour_end')->dropDownList($hour_list) ?>

    <?= $form->field($model, 'act_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'real_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'full_work_performed')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'workers_number')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'engineer_id')->textInput() ?>

    <?//= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'payment')->textInput() ?>
    <?= $form->field($model, 'payment')->dropDownList(array('0'=>'No', '10'=>'Waiting', '1'=>'Yes')) ?>

    <?= $form->field($model, 'summary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'own_equip_sum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <?//= $form->field($model, 'act_pdf')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'act_scan')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'pics')->textInput(['maxlength' => true]) ?>

    <div class="form-group field-files-url">
        <label class="control-label" for="files-url">PDF</label>
        <?php foreach ($model->files as $pics){
            if ($pics->type=='act_pdf') {?>
                <div class="pdf">
                    <?echo Html::a($pics->name, [$pics->url], ['class' => 'link-download', 'target' => '_blank']);?>
                    <?echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['#'], ['class' => 'link-del', 'data-href' => '/files/delete-image', 'data-id' => $pics->id]);?>
                </div>
            <?}
        }?>
        <?
        echo FileInput::widget([
            'name' => 'act_pdf[]',
            'options'=>[
                'multiple'=>true
            ]
        ]);
        ?>

        <!--<input type="hidden" name="act_pdf[]" value=""><input type="file" id="files-url" name="act_pdf[]" multiple="">-->
<!--        <div class="inputfile"><label><span>Прикрепить файл</span><input type="file" id="files-url" name="act_pdf[]" multiple=""></label></div>-->
        <div class="help-block"></div>
    </div>
    <div class="form-group field-files-url">
        <label class="control-label" for="files-url">Сканы</label>
        <?php foreach ($model->files as $pics){
            if ($pics->type=='act_scan') {?>
                <div class="pic">
                    <?echo Html::a(Html::img($pics->url, $options = ['class' => 'postImg', 'style' => ['width' => '180px']]), [$pics->url], ['class' => 'link-download', 'target' => '_blank']);?>
                    <?echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['#'], ['class' => 'link-del', 'data-href' => '/files/delete-image', 'data-id' => $pics->id]);?>
                </div>
            <?}
        }?>
        <?
        echo FileInput::widget([
            'name' => 'act_scan[]',
            'options'=>[
                'multiple'=>true,
                'accept' => 'image/*'
            ],
            'pluginOptions' => ['previewFileType' => 'image']
        ]);
        ?>
        <!--<input type="hidden" name="act_scan[]" value=""><input type="file" id="files-url" name="act_scan[]" multiple="" accept="image/*">-->
<!--        <div class="inputfile"><label><span>Прикрепить файл</span><input type="file" id="files-url" name="act_scan[]" multiple="" accept="image/*"></label></div>-->
        <div class="help-block"></div>
    </div>
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

<!--    --><?//= $form->field($modelPics, 'url[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
<!---->
<!--    --><?//= $form->field($modelPics, 'url[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
<!---->
<!--    --><?//= $form->field($modelPics, 'url[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
