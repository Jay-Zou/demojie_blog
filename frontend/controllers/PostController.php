<?php
namespace frontend\controllers;

/**
 * Created by PhpStorm.
 * User: 14261
 * Date: 2018/4/11
 * Time: 下午 8:14
 */

use common\models\CatModel;
use common\models\PostModel;
use frontend\controllers\base\BaseController;
use frontend\models\PostForm;

/**
 * 文章控制器
 * Class PostController
 * @package frontend\controllers
 */
class PostController extends BaseController
{

    public function actions()
    {
        return [
            // 文件上传组件
            'upload'=>[
                'class' => 'common\widgets\file_upload\UploadAction',     //这里扩展地址别写错
                'config' => [
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}",
                ]
            ],
            // 富文本编辑器组件
            'ueditor'=>[
                'class' => 'common\widgets\ueditor\UeditorAction',
                'config'=>[
                    //上传图片配置
                    'imageUrlPrefix' => "", /* 图片访问路径前缀 */
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                ]
            ]
        ];
    }
    /**
     * 文章列表
     * @return string
     */
    public function actionIndex()
    {

        return $this->render('index');
    }


    /**
     * 创建文章
     * @return string
     */
    public function actionCreate()
    {
        $res = $_REQUEST;
        $model = new PostForm();
        // 获取所有分类
        $cats = CatModel::getAllCats();

        return $this->render('create', ['model' => $model, 'cats' => $cats]);
    }

}