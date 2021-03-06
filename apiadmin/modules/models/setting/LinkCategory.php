<?php

namespace apiadmin\modules\models\setting;

use Yii;

/**
 * This is the model class for table "link_category".
 *
 * @property int $id
 * @property string $category
 */
class LinkCategory extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'link_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category'], 'required'],
            [['category'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
        ];
    }

    public function getLink()
    {
        return $this->hasMany(Link::className(),['link_category_id'=>'id']);
    }

    public static function getAll()
    {
        return self::find()->joinWith('link')->asArray()->all();
    }
}
