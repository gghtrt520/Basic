<?php

namespace apiadmin\modules\models\setting;

use Yii;

/**
 * This is the model class for table "banner_list".
 *
 * @property int $id
 * @property int $loop_banner_id
 * @property string $attr
 * @property string $path
 * @property string $href 是否存在链接
 */
class BannerList extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'banner_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['loop_banner_id', 'url'], 'required'],
            [['loop_banner_id'], 'integer'],
            [['attr', 'path', 'href'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'loop_banner_id' => 'Loop Banner ID',
            'attr' => 'Attr',
            'url'  => 'Url',
            'href' => 'Href',
        ];
    }
}
