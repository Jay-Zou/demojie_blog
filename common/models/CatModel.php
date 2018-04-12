<?php

namespace common\models;

use common\models\base\BaseModel;
use Yii;

/**
 * This is the model class for table "cats".
 *
 * @property int $id 自增ID
 * @property string $cat_name 分类名称
 */
class CatModel extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'cat_name' => Yii::t('common', 'Cat Name'),
        ];
    }

    /**
     * 获取所有分类
     * @return array
     */
    public static function getAllCats()
    {
        $cats = ['0' => '暂无分类'];
        $items = self::find()->asArray()->all();

        if ($items) {
            foreach ($items as $item) {
                $cats[$item['id']] = $item['cat_name'];
            }
        }
        return $cats;
    }
}
