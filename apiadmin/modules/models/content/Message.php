<?php

namespace apiadmin\modules\models\content;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $message
 * @property string $created_at
 */
class Message extends \common\models\Base
{

    public function behaviors()
    {
        return [
            [
                'class'              => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'created_at',
                'value'              => date('Y-m-d H:i:s',time()),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'message'], 'required'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 60],
            [['phone'], 'string', 'max' => 30],
            [['email'], 'string', 'max' => 100],
            [['message'], 'string', 'max' => 255],
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
            'phone' => 'Phone',
            'email' => 'Email',
            'message' => 'Message',
            'created_at' => 'Created At',
        ];
    }
}
