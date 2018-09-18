<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;
use Yii;

class UploadForm extends Model
{
    /**
    * @var UploadedFile[]
    */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4,'checkExtensionByMimeType'=>false],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $filename=Yii::$app->getSecurity()->generateRandomString(15);
                // echo $filename;
                $file->saveAs('uploads/' . $filename . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}
?>