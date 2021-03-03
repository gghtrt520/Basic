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

    
    public function getSubMenu()
    {
        return self::find()->where(['parent_id'=>$this->id])->asArray()->all();
    }


    public  function searchModel(&$page)
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
        $params['parent_id'] = 0;
        $find                = self::find()->filterWhere($params);
        $params['page_size'] = Yii::$app->request->post('page_size',self::PAGE_SIZE);
        $pagination   = new \yii\data\Pagination([
            'totalCount' => $find->count(),
            'pageSize'   => $params['page_size'],
        ]);
        $page = $find->count();
        $all  = $find->offset($pagination->offset)->limit($pagination->limit)->all();
        if($all){
            foreach ($all as $value) {
                $data['id']        = $value->id;
                $data['name']      = $value->name;
                $data['parent_id'] = $value->parent_id;
                $data['path']      = $value->path;
                $data['sort']      = $value->sort;
                $data['submenu']   = $value->getSubMenu();
                $return[] = $data;
            }
        }
        return $return?:[];
    }
}
