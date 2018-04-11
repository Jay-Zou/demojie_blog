<?php
/**
 * Created by PhpStorm.
 * UserModel: 14261
 * Date: 2018/3/31
 * Time: 下午 6:18
 */

$config =  [

];

// 把主目录的语言包文件包含进来
return yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../../messages/zh-CN/common.php',
    $config
);