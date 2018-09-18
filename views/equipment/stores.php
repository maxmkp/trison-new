<?php

use yii\helpers\Html;
?>
    <option value="">Укажите магазин</option>
<?foreach ($model as $option) {?>
    <option value="<?=$option->id?>"><?=$option->store_name?></option>
<?}?>