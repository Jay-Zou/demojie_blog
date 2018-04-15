<?php
/**
 * Created by PhpStorm.
 * User: 14261
 * Date: 2018/4/11
 * Time: 下午 10:28
 */

namespace frontend\models;

use common\models\PostModel;
use common\models\TagModel;
use Yii;
use yii\base\Model;
use yii\db\Exception;

/**
 * 标签的表单模型
 *
 * Class TagForm
 * @package frontend\models
 */
class TagForm extends Model
{
    public $id;

    public $tags;

    public function rules()
    {
        return [
            ['tags', 'required'],
            ['tags', 'each', 'rule' => ['string']],
        ];
    }

    /**
     * 保存标签数组
     *
     * @return array
     * @throws \Exception
     */
    public function saveTags()
    {

        // 保存新增或更新后标签的ID
        $ids = [];
        // 如果传过来的$this->tags不是数组，而是用逗号分隔的字符串
        if (!is_array($this->tags)) {
            // 首先替换中文逗号
            $tags = str_replace('，', ',', $this->tags);
            // 然后根据英文逗号分割
            $this->tags = preg_split('/,/', $tags);
        }

        if (!empty($this->tags)) {
            foreach ($this->tags as $tag) {
                // 去除两端空格
                $tag = trim($tag);
                $ids[] = $this->_saveTag($tag);
            }
        }

        return $ids;
    }

    /**
     * 保存一个标签
     * 如果不存在则新增，存在则文章数+1
     *
     * @param $tag 标签名
     * @return mixed 返回的ID
     * @throws \Exception
     */
    private function _saveTag($tag)
    {
        $model = new TagModel();
        // 查询标签是否存在
        $res = $model->find()->where(['tag_name' => $tag])->one();
        // 如果不存在则创建，并初始化 post num = 1
        // 如果存在则post num +1
        if (!$res) {
            $model->tag_name = $tag;
            $model->post_num = 1;
            if (!$model->save()) {
                throw new \Exception('保存标签失败！');
            }
            return $model->id;
        } else {
            // 将某个字段的值加1
            $res->updateCounters(['post_num' => 1]);
        }

        // 如果是新增，则会填充$model->id，如果是修改，则前面已经查询出$model
        return $res->id;

    }
}