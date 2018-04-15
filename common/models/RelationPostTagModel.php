<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "relation_post_tags".
 *
 * @property int $id 自增ID
 * @property int $post_id 文章ID
 * @property int $tag_id 标签ID
 */
class RelationPostTagModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'relation_post_tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'tag_id'], 'integer'],
            [['post_id', 'tag_id'], 'unique', 'targetAttribute' => ['post_id', 'tag_id']],
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
            'tag_id' => Yii::t('common', 'Tag ID'),
        ];
    }

    /**
     * tag_id <=> tags 一对一关联
     * @return \yii\db\ActiveQuery
     */
    public  function getTag()
    {
        return $this->hasOne(TagModel::class, ['id' => 'tag_id']);
    }
}
