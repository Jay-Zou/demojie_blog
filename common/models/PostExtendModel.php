<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "post_extends".
 *
 * @property int $id 自增ID
 * @property int $post_id 文章ID
 * @property int $browser 浏览量
 * @property int $collect 收藏量
 * @property int $praise 点赞
 * @property int $comment 评论
 */
class PostExtendModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_extends';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'browser', 'collect', 'praise', 'comment'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'post_id' => Yii::t('common', 'Post ID'),
            'browser' => Yii::t('common', 'Browser'),
            'collect' => Yii::t('common', 'Collect'),
            'praise' => Yii::t('common', 'Praise'),
            'comment' => Yii::t('common', 'Comment'),
        ];
    }

    /**
     * 更新指定记录的字段的值
     * @param $condition 查询条件
     * @param $attribute 需要更新的字段
     * @param $num 需要更新的值
     */
    public function upCounter($condition, $attribute, $num)
    {
        $counter = $this->findOne($condition);
        if (!$counter) {
            // 如果记录不存在，则插入，并将对应属性的值复位$num
            $this->setAttributes($condition);
            $this->$attribute = $num;
            $this->save();
        } else {
            // 如果记录存在，则将对应属性的值 + $num
            $countData[$attribute] = $num;
            $counter->updateCounters($countData);
        }

    }
}
