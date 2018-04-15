<?php
namespace frontend\controllers;

/**
 * Created by PhpStorm.
 * User: 14261
 * Date: 2018/4/11
 * Time: 下午 8:14
 */

use common\models\PostExtendModel;
use Yii;
use common\models\CatModel;
use common\models\PostModel;
use frontend\controllers\base\BaseController;
use frontend\models\PostForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
/**
 * 文章控制器
 * Class PostController
 * @package frontend\controllers
 */
class PostController extends BaseController
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'upload', 'ueditor'],
                'rules' => [
                    [
                        // 不加roles表示所有都可以访问
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                    [
                        // @登录之后才能访问，？登录之前才能访问
                        'actions' => ['create', 'upload', 'ueditor'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    '*' => ['get', 'post'],     // 图片上传也需要用post
                ],
            ],
        ];
    }

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
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $model = new PostForm();

        // 定义场景
        $model->setScenario(PostForm::SCENARIOS_CREATE);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!$model->create()) {
                // 如果保存失败，在页面上显示错误信息
                Yii::$app->session->setFlash('error', $model->_lastError);
            } else {
                // 保存成功，跳转到文章详情
                return $this->redirect(['post/view', 'id' => $model->id]);
            }
        }

        // 获取所有分类
        $cats = CatModel::getAllCats();

        return $this->render('create', ['model' => $model, 'cats' => $cats]);
    }

    /**
     * 文章详情
     */
    public function actionView($id)
    {
        $model = new PostForm();
        $data = $model->getViewById($id);

        // 做一下文章扩展操作，统计
        $model = new PostExtendModel();
        $model->upCounter(['post_id' => $id], 'browser', 1);

        return $this->render('view', ['data' => $data]);
    }

}