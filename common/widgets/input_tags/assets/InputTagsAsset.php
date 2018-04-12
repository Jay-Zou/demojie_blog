<?php
/**
 * Created by PhpStorm.
 * User: 14261
 * Date: 2018/4/12
 * Time: 下午 6:33
 */
namespace common\widgets\input_tags\assets;

use yii\web\AssetBundle;

class InputTagsAsset extends AssetBundle
{
    public $css = [
        'css/bootstrap-tagsinput.css',
    ];

    public $js = [
        'js/bootstrap-tagsinput.js',
        'js/my_test.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    /**
     * 初始化：sourcePath赋值
     * @see \yii\web\AssetBundle::init()
     */
    public function init()
    {
        $this->sourcePath = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR . 'statics';
    }
}