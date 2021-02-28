<?php

namespace apiadmin\modules\models\content;

use Yii;

/**
 * This is the model class for table "content".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $image
 * @property string $is_available
 * @property int $menu_id
 * @property int $user_id
 * @property string $create_time
 */
class Content extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content', 'is_available'], 'string'],
            [['menu_id', 'user_id'], 'integer'],
            [['create_time'], 'safe'],
            [['title', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'image' => 'Image',
            'is_available' => 'Is Available',
            'menu_id' => 'Menu ID',
            'user_id' => 'User ID',
            'create_time' => 'Create Time',
        ];
    }
}
