<?php

namespace common\models;

use common\models\base\BaseModel;
use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property int $id 自增ID
 * @property string $title 标题
 * @property string $summary 摘要
 * @property string $content 内容
 * @property string $label_img 标签图
 * @property int $cat_id 分类id
 * @property int $user_id 用户id
 * @property string $user_name 用户名
 * @property int $is_valid 是否有效：0-未发布 1-已发布
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class PostModel extends BaseModel
{
    const IS_VALID = 1; // 发布
    const NO_VALID = 0; // 未发布
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['cat_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'summary', 'label_img', 'user_name'], 'string', 'max' => 255],
            [['is_valid'], 'integer', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'title' => Yii::t('common', 'Title'),
            'summary' => Yii::t('common', 'Summary'),
            'content' => Yii::t('common', 'Content'),
            'label_img' => Yii::t('common', 'Label Img'),
            'cat_id' => Yii::t('common', 'Cat ID'),
            'user_id' => Yii::t('common', 'User ID'),
            'user_name' => Yii::t('common', 'User Name'),
            'is_valid' => Yii::t('common', 'Is Valid'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
        ];
    }

    /**
     * 关联关系，获取文章所关联的标签
     */
    public function getRelate()
    {
        // 一对多
        return $this->hasMany(RelationPostTagModel::class, ['post_id' => 'id']);
    }

    /**
     * 关联关系，获取文章的扩展属性
     * @return \yii\db\ActiveQuery
     */
    public function getExtend()
    {
        return $this->hasOne(PostExtendModel::class, ['post_id' => 'id']);
    }


}
