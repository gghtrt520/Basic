<?php

namespace apiadmin\modules\models\content;

use Yii;

/**
 * This is the model class for table "link".
 *
 * @property int $id
 * @property int $link_category_id
 * @property string $name
 * @property string $href
 */
class Link extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link_category_id', 'name', 'href'], 'required'],
            [['link_category_id'], 'integer'],
            [['name', 'href'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link_category_id' => 'Link Category ID',
            'name' => 'Name',
            'href' => 'Href',
        ];
    }
}
