<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model app\models\Files */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => ucfirst($model->table_name).' list', 'url' => '/'.$model->table_name.'/'];
$this->params['breadcrumbs'][] = ['label' => 'Parent '.$model->table_name, 'url' => '/'.$model->table_name.'/view?id='.$linked_model->id];
//$this->params['breadcrumbs'][] = $this->title;
?>
<?= Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>
<div class="files-view">

    <h1><?= Html::encode($this->title) ?><a class="printIframe" href="<?=$model->url?>"></a></h1>

    <iframe name="iframePrint" src="<?=$model->url?>"
            style="width: 100%; height: 800px;" frameborder="0">Ваш браузер не поддерживает фреймы</iframe>

</div>
