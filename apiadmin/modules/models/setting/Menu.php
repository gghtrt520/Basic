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
        return 'menu';
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
        $return = [];
        $data = self::find()->where(['parent_id'=>$this->id])->all();
        if($data){
            foreach ($data as $value) {
                $one['id']         = $value->id;
                $one['label']      = $value->name;
                $one['parent_id']  = $value->parent_id;
                $one['path']       = $value->path;
                $one['sort']       = $value->sort;
                $one['children']   = $value->getSubMenu();
                $return[] = $one;
            }
        }
        return $return;
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
            'page'       => Yii::$app->request->post('page',0)-1
        ]);
        $page = $find->count();
        $all  = $find->offset($pagination->offset)->limit($pagination->limit)->all();
        if($all){
            foreach ($all as $value) {
                $data['id']         = $value->id;
                $data['label']      = $value->name;
                $data['parent_id']  = $value->parent_id;
                $data['path']       = $value->path;
                $data['sort']       = $value->sort;
                $data['children']   = $value->getSubMenu();
                $return[] = $data;
            }
        }
        return $return?:[];
    }
}
