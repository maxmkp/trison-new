<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CountriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Countries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="countries-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?if (!empty($shown_cols)) {
    $decoded_shown_cols = json_decode($shown_cols);
    if (!empty($decoded_shown_cols->countries)) {
    $index_shown_cols = explode(',', $decoded_shown_cols->countries);?>
    <div id="db_cols" class="<?foreach ($index_shown_cols as $index_shown_col) echo "show".$index_shown_col.' ';?>">
        <?}
        }?>

    <p>
        <?= Html::a('Create Countries', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
            'id' => 'countries'
        ],
    ]); ?>
</div>
