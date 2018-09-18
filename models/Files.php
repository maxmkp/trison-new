<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "files".
 *
 * @property integer $id
 * @property string $name
 */
class Files extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'table_name', 'url'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => 'png, jpg, jpeg, JPG', 'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'url' => 'URL',
            'type' => 'тип',
            'table_name' => 'Таблица',
        ];
    }

    static function saveFiles($modelName, $model_id, $arFiles) {
        $arChanges = array();
        if (!empty($arFiles)) {
            $dirName = "uploads/".$modelName;
            if (!file_exists($dirName)) {
                mkdir($dirName);
            }
        }
        foreach ($arFiles as $type => $files_array) {
            if ($files_array['error'][0] != 4) {
                $dirNameForType = "uploads/".$modelName."/".$type;
                if (!file_exists($dirNameForType)) {
                    mkdir($dirNameForType);
                }

                $arChanges[$type]=array('from'=>'', 'to'=>'new file uploaded:');

                $filesInstances = UploadedFile::getInstancesByName($type);

                foreach ($filesInstances as $file_up) {
                    $modelPics = new Files();
                    $modelPics->file = $file_up;
                    $filename = time() . Yii::$app->getSecurity()->generateRandomString(5);
                    $modelPics->file->saveAs('uploads/'.$modelName.'/' . $type . '/' . $filename . '.' . $file_up->extension);
                    $modelPics->type = $type;
                    $modelPics->table_name = $modelName;
                    $modelPics->name = $file_up->name;
                    $modelPics->link_id = $model_id;
                    $modelPics->url = '/uploads/'.$modelName.'/' . $type . '/' . $filename . '.' . $file_up->extension;
                    if (($type == 'act_pdf')||($type == 'attach')) {
                        $modelPics->save(false);
                    } else {
                        $modelPics->save();
                    }
                    $arChanges[$type]['to'] .= '<br>http://'.$_SERVER['HTTP_HOST'].$modelPics->url;
                }
            }
        }
        return $arChanges;
    }

}
