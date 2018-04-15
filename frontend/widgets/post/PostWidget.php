<?php
/**
 * Created by PhpStorm.
 * User: 14261
 * Date: 2018/4/13
 * Time: 下午 8:30
 */

namespace frontend\widgets\post;

use common\models\PostModel;
use frontend\models\PostForm;
use Yii;
use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\Url;

/**
 * 文章列表组件
 * Class PostWidget
 * @package frontend\widgets\post
 */
class PostWidget extends Widget
{
    /**
     * 文章列表的标题
     * @var string
     */
    public $title = '';

    /**
     * 显示条数
     * @var int
     */
    public $limit = 6;

    /**
     * 是否显示更多
     * @var bool
     */
    public $more = true;

    /**
     * 是否显示分页
     * @var bool
     */
    public $page = false;

    public function run()
    {
        // 小部件中可以获取调用组件那个页面的请求参数
        $curPage = Yii::$app->request->get('page', 1);
        // 查询条件
        $cond = ['=', 'is_valid', PostModel::IS_VALID];
        $res = PostForm::getList($cond, $curPage, $this->limit);

        // 如果页面没设置标题，则显示默认标题
        $result['title'] = $this->title ? : '最新文章';
        // 显示更多，跳转到文章首页
        $result['more'] = Url::to(['post/index']);
        // body存放查询出来的数据
        $result['body'] = $res['data'] ? : [];
        // 是否显示分页
        if ($this->page) {
            // 前端分页器
            $pages = new Pagination(['totalCount' => $res['count'], 'pageSize' => $res['pageSize']]);
            $result['page'] = $pages;

        }


        return $this->render('index', ['data' => $result]);
    }
}