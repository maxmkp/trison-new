<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use app\models\Stores;
use app\models\Equipment;
use app\models\Cities;
use app\models\User;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Accident */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="accident-form">

<!--    --><?//if (!empty($getParams['store_id'])) $store_options=array($getParams['store_id']=>array('Selected'=>true)); else $store_options=array();?>
    <?if (!empty($getParams['equip_id'])) $equip_options=array($getParams['equip_id']=>array('Selected'=>true)); else $equip_options=array();?>
    <?if (!empty($model->store_id)) $getParams['store_id']=$model->store_id;?>
    <?if (!empty($getParams['store_id'])) $equip_obj=Equipment::find()->where(['store_id' => $getParams['store_id']])->all(); else $equip_obj=Equipment::find()->all();?>


    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'acc_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'act_number')->textInput(['maxlength' => true]) ?>

    <?if (!empty($model->act_date)) $model->act_date=date('d.m.Y', strtotime($model->act_date));?>
    <?= $form->field($model, 'act_date')->widget(DatePicker::classname(), [
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

    <?= $form->field($model, 'priority')->dropDownList(['low'=>'low', 'medium'=>'medium', 'high'=>'high'], array('prompt' => 'Укажите приоритет')) ?>

    <?//= $form->field($model, 'store_id')->textInput() ?>

    <?if (!empty($getParams['store_id'])) {?>

        <?= $form->field($model, 'store_id')->hiddenInput(['value' => $getParams['store_id']]) ?>
        <?= Html::input('text', 'store_id_show', Stores::find()->where(['id'=>$getParams['store_id']])->one()->store_name, ['readonly' => true, 'class' => 'form-control', 'style' => 'margin-bottom:15px;margin-top:-15px;']) ?>

        <?= $form->field($model, 'equipment_id')->dropDownList(ArrayHelper::map($equip_obj, 'id', 'equip'), array('prompt' => 'Укажите оборудование', 'options' => $equip_options)) ?>

    <?} else {?>

        <div class="form-group">

            <label class="control-label">City</label>

            <?= Html::dropDownList('city_id_show', 0, ArrayHelper::map(Cities::find()->all(), 'id', 'name'), ['prompt' => 'Укажите город', 'id' => 'city_id_show', 'class' => 'form-control ajax', 'data-href' => '/accident/get-stores']) ?>

        </div>

        <?= $form->field($model, 'store_id')->dropDownList(array(), array('prompt' => 'Укажите магазин', 'class' => 'after_city_id_show form-control ajax', 'disabled' => true, 'data-href' => '/accident/get-equip')) ?>

        <?= $form->field($model, 'equipment_id')->dropDownList(array(), array('prompt' => 'Укажите оборудование', 'class' => 'form-control after_accident-store_id', 'disabled' => true)) ?>

    <?}?>
    <?//= $form->field($model, 'equipment_id')->textInput() ?>

    <?= $form->field($model, 'fault')->textInput(['maxlength' => true]) ?>

    <?if (!empty($getParams['responsible'])) $resp_options=array($getParams['responsible']=>array('Selected'=>true)); else $resp_options=array();?>
    <?$responsible_users_obj=User::find()->all();?>
    <?= $form->field($model, 'responsible')->dropDownList(ArrayHelper::map($responsible_users_obj, 'id', 'username'), array('prompt' => 'Укажите ответственного', 'options' => $resp_options)) ?>

    <?if (!empty($getParams['accountant'])) $accountant_options=array($getParams['accountant']=>array('Selected'=>true)); else $accountant_options=array();?>
    <?$accountant_users_obj=User::find()->all();?>
    <?= $form->field($model, 'accountant')->dropDownList(ArrayHelper::map($accountant_users_obj, 'id', 'username'), array('prompt' => 'Укажите бухгалтера', 'options' => $accountant_options)) ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

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
