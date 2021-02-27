<?php
namespace common\models;

use Yii;

class Base extends \yii\db\ActiveRecord
{
    /**
     * 分页默认参数
     */
    const PAGE_SIZE = 10;

    /**
     * 分页函数
     */
    public  function queryList()
    {
        $attributes = $this->attributes();
        $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());
        if($params){
            foreach ($params as $key => $value) {
                if(!in_array($key,$attributes)){
                    unset($params[$key]);
                }   
            }
        }
        $find                = self::find()->filterWhere($params);
        $params['page_size'] = Yii::$app->request->post('page_size',self::PAGE_SIZE);
        $pagination   = new \yii\data\Pagination([
            'totalCount' => $find->count(),
            'pageSize'   => $params['page_size'],
        ]);
        return $find->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
    }
}