<?php

use yii\helpers\Html;
use app\models\Stores;
use app\models\Accident;
?>
    <option value="">Укажите оборудование</option>
<?foreach ($model as $option) {?>
    <option value="<?=$option->id?>"><?=$option->equip?></option>
<?}?>