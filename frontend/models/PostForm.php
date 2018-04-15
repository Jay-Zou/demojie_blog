<?php
/**
 * Created by PhpStorm.
 * User: 14261
 * Date: 2018/4/11
 * Time: 下午 10:28
 */

namespace frontend\models;

use common\models\PostModel;
use common\models\RelationPostTagModel;
use common\models\TagModel;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\db\Query;

/**
 * 文章表单模型
 * Class PostForm
 * @package frontend\models
 */
class PostForm extends Model
{
    // 取出前台需要填入的字段
    public $id;
    public $title;
    public $content;
    public $label_img;
    public $cat_id;
    public $tags;

    public $_lastError = "";

    /**
     * 定义场景
     * SCENARIOS_CREATE 创建
     * SCENARIOS_UPDATE 更新
     */
    const SCENARIOS_CREATE = 'create';
    const SCENARIOS_UPDATE = 'update';

    /**
     * 定义事件
     * EVENT_AFTER_CREATE 创建之后的事件
     * EVENT_AFTER_UPDATE 更新之后的事件
     */
    const EVENT_AFTER_CREATE = "eventAfterCreate";
    const EVENT_AFTER_UPDATE = "eventAfterUpdate";

    /**
     * 场景设置
     * @return array|void
     */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIOS_CREATE => ['title', 'content', 'label_img', 'cat_id', 'tags'],
            self::SCENARIOS_UPDATE => ['title', 'content', 'label_img', 'cat_id', 'tags'],
        ];

        return array_merge(parent::scenarios(), $scenarios);
    }


    /**
     * 前台规则验证
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'title', 'content', 'cat_id'], 'required'],
            [['id', 'cat_id'], 'integer'],
            ['title', 'string', 'min' => 4, 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'title' => Yii::t('common', 'Title'),
            'content' => Yii::t('common', 'Content'),
            'label_img' => Yii::t('common', 'Label Img'),
            'cat_id' => Yii::t('common', 'Cat ID'),
            'tags' => Yii::t('common', 'Tags'),
        ];
    }


    /**
     * 文章创建
     * @return bool
     * @throws \yii\db\Exception
     */
    public function create()
    {
        // 多表操作，需要事务
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = new PostModel();
            $model->setAttributes($this->attributes);
            $model->summary = $this->_getSummary();
            if (empty(Yii::$app->user->identity->id)) {
                throw new \Exception('你还没有登录，请登录！');
            }
            $model->user_id = Yii::$app->user->identity->id;
            $model->user_name = Yii::$app->user->identity->username;
            $model->is_valid = PostModel::IS_VALID;
            $model->created_at = time();
            $model->updated_at = time();
            if (!$model->save()) {
                throw new \Exception('文章保存失败!');    // 语言包
            }
            // 创建文章后，返回其ID
            $this->id = $model->id;

            // 调用事件
            // 将当期表单数据，与模型保存后的数据，合并，后者覆盖前者
            $data = array_merge($this->getAttributes(), $model->getAttributes());
            $this->_evenAfterCreate($data);

            $transaction->commit();
            return  true;
        } catch(\Exception $e) {
            // 创建失败
            // 回滚事务
            $transaction->rollBack();
            // 将跑出的异常转换成错误信息
            $this->_lastError = $e->getMessage();
            return false;
        }
    }

    public function getViewById($id)
    {
        // 查询，顺便查询关联表
        $res = PostModel::find()->with('relate.tag', 'extend')->where(['id' => $id])->asArray()->one();

        if (!$res) {
            throw new \Exception('文章不存在!');
        }

        $res['tags'] = [];
        if (isset($res['relate']) && !empty($res['relate'])) {
            foreach ($res['relate'] as $list) {
                $res['tags'][] = $list['tag']['tag_name'];
            }
        }

        unset($res['relate']);
        return $res;
    }

    /**
     * 截取文章摘要
     * @param int $start
     * @param int $end
     * @param string $charest
     * @return null|string
     */
    private function _getSummary($start = 0, $end = 90, $charest = 'utf-8')
    {
        if (empty($this->content)) {
            return null;
        }

        return mb_substr(str_replace('&nbsp;', '', strip_tags($this->content)), $start, $end, $charest);
    }

    /**
     * 创建完成后调用事件
     */
    private function _evenAfterCreate($data)
    {
        // 添加事件
        $this->on(self::EVENT_AFTER_CREATE, [$this, '_eventAddTag'], $data);    // 添加标签事件，注意事件方法必须为public
        // 出发事件
        $this->trigger(self::EVENT_AFTER_CREATE);

        // 增加积分，修改玩家其他数据，....

    }

    /**
     * 添加标签
     * @param $event
     * @throws \Exception
     */
    public function _eventAddTag($event)
    {
        // 保存标签
        $tagForm = new TagForm();
        $tagForm->tags = $event->data['tags'];
        $tagids = $tagForm->saveTags();

        // 由于这个方法还要用户修改文章
        // 所以需要先删除当前文章的所有标签
        RelationPostTagModel::deleteAll(['post_id' => $event->data['id']]);
        // todo 要将标签表的文章数 -1，首先根据post_id查询对应的tag_id，然后调用

        // 批量保存文章和标签的关联关系
        if (!empty($tagids)) {
            foreach ($tagids as $key => $id) {
                $row[$key]['post_id'] = $this->id;
                $row[$key]['tag_id'] = $id;
            }

            // 批量插入
            $res = (new Query())->createCommand()->batchInsert(RelationPostTagModel::tableName(), ['post_id', 'tag_id'], $row)->execute();

            // 如果插入失败
            if (!$res) {
                throw new \Exception('关联关系保存失败！');
            }
        }

    }

    /**
     * 分页获取文章列表
     * @param $cond
     * @param int $curPage
     * @param int $pageSize
     * @param array $orderBy
     * @return array
     */
    public static function getList($cond, $curPage = 1, $pageSize = 5, $orderBy = ['id' => SORT_DESC])
    {
        $model = new PostModel();
        // 定义要查询的字段
        $select = [
            'id',
            'title',
            'summary',
            'label_img',
            'cat_id',
            'user_id',
            'user_name',
            'is_valid',
            'created_at',
            'updated_at'

        ];
        $query = $model->find()
            ->select($select)
            ->where($cond)
            ->with('relate.tag', 'extend')
            ->orderBy($orderBy);

        // 封装按照分页属性查询数据到BaseModel，提高复用性
        $res = $model->getPages($query, $curPage, $pageSize);
        // 格式化数据
        $res['data'] = self::_formatList($res['data']);

        return $res;

    }

    /**
     * 格式化数据，将一些关联字段的值取出来
     * @param $data
     * @return mixed
     */
    public static function _formatList($data)
    {
        // 遍历每条文章的信息
        foreach ($data as &$list) {
            $list['tags'] = [];
            // 取出关联标签的数据
            if (isset($list['relate']) && !empty($list['relate'])) {
                foreach ($list['relate'] as $lt) {
                    $list['tags'][] = $lt['tag']['tag_name'];
                }
            }
            unset($list['relate']);
        }
        return $data;
    }

}