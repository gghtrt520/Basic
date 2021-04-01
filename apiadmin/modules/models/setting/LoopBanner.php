<?php

namespace apiadmin\modules\models\setting;

use Yii;

/**
 * This is the model class for table "loop_banner".
 *
 * @property int $id
 * @property string $title
 */
class LoopBanner extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'loop_banner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
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
        ];
    }

    public function getBanner()
    {
        return $this->hasMany(BannerList::className(),['loop_banner_id'=>'id']);
    }

    public static function getAll()
    {
        return self::find()->joinWith('banner')->asArray()->all();
    }
}
