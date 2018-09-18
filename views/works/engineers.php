<?php

use yii\helpers\Html;
?>
    <option value="">Укажите инженера</option>
<?foreach ($model as $option) {?>
    <option value="<?=$option->id?>"><?=$option->name?></option>
<?}?>