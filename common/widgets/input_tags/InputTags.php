<?php
/**
 * Created by PhpStorm.
 * User: 14261
 * Date: 2018/4/12
 * Time: 下午 6:32
 */
namespace common\widgets\input_tags;

use common\widgets\input_tags\assets\InputTagsAsset;
use yii\widgets\InputWidget;
use yii\helpers\Html;

class InputTags extends InputWidget
{
    public $config = [];

    public function init()
    {

    }

    public function run()
    {
        $this->registerClientScript();
        if ($this->hasModel()) {
            $a1 = Html::getAttributeName($this->attribute);
            $inputId = Html::getInputId($this->model, $this->attribute);
            $inputName = Html::getInputName($this->model, $this->attribute);
            $inputValue = Html::getAttributeValue($this->model, $this->attribute);
            return $this->render('index',[
                'config'=>$this->config,
                'inputName' => $inputName,
                'inputValue' => $inputValue,
                'inputId' => $inputId,
            ]);
        } else {
            return ;
        }
    }


    public function registerClientScript()
    {
        InputTagsAsset::register($this->view);
    }
}