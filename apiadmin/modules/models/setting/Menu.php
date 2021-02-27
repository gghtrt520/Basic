<?php

namespace apiadmin\modules\models\setting;

use Yii;

/**
 * This is the model class for table "Menu".
 *
 * @property int $id
 * @property string $name 菜单名称
 * @property int $parent_id
 * @property string $path 路由地址
 * @property int $sort 排序规则
 */
class Menu extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent_id' => 'Parent ID',
            'path' => 'Path',
            'sort' => 'Sort',
        ];
    }
}
