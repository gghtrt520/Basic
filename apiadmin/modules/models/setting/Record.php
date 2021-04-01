<?php

namespace apiadmin\modules\models\setting;

use Yii;

/**
 * This is the model class for table "record".
 *
 * @property int $id
 * @property string $date
 * @property int $times
 */
class Record extends \common\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'times'], 'required'],
            [['date'], 'safe'],
            [['times'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'times' => 'Times',
        ];
    }

    public static function getAll()
    {
        $today = self::findOne(['date'=>date('Y-m-d')]);
        $all   = self::find()->count();
        return [
            'today' => $today ? $today->times : 0,
            'all'   => $all
        ];
    }
}
