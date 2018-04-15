<?php
namespace common\models\base;
/**
 * Created by PhpStorm.
 * User: 14261
 * Date: 2018/4/11
 * Time: 下午 9:03
 */

use yii\db\ActiveRecord;

/**
 * 基础模型
 * Class BaseModel
 * @package common\models\base
 */
class BaseModel extends ActiveRecord
{


    /**
     * 公共的获取分页数据大小
     * @param $query 数据库查询的handler，由于子类要设置条件，所以就不在父类中获取了。
     * @param int $curPage 当前页
     * @param int $pageSize 每页条数
     * @param array $search 需要搜索的的条件
     * @return array
     */
    public function getPages($query, $curPage = 1, $pageSize = 10, $search = null)
    {
        if ($search)
            $query->andFilterWhere($search);
        // 总记录数
        $data['count'] = $query->count();
        if (!$data['count']) {
            // 如果数据库中没有数据，则返回0数据的格式。
            // 必须的，不然之后的地方会报错
            return ['count' => 0, 'curPage' => $curPage, 'pageSize' => $pageSize, 'start' => 0, 'end' => 0, 'data' => []];
        }
        // ceil()进一，超过总页数时，显示最后一页
        $curPage = (ceil($data['count']/$pageSize) < $curPage) ? ceil($data['count']/$pageSize) : $curPage;
        // 当前页
        $data['curPage'] = $curPage;
        // 当前页要查询的起始记录编号
        $data['start'] = ($curPage - 1) * $pageSize + 1;
        // 当前页要查询的结束记录编号
        // 如果最后一页，则结束编号为总记录数；否则，则为当前页*每页记录数
        $data['end'] = (ceil($data['count']/$pageSize) == $curPage) ? $data['count'] : $curPage * $pageSize;
        // 每页记录数
        $data['pageSize'] = $pageSize;
        // 数据
        // offset 是从0开始算的
        $data['data'] = $query->offset($data['start'] - 1)->limit($pageSize)->asArray()->all();

        return $data;
    }
}