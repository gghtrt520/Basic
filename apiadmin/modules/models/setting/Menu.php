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
                if(Yii::$app->controller->id == 'api/frontend'){
                    $one['content']    = $value->content;
                }
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
        $page = $find->count();
        $all  = $find->all();
        if($all){
            foreach ($all as $value) {
                $data['id']         = $value->id;
                $data['label']      = $value->name;
                $data['parent_id']  = $value->parent_id;
                $data['path']       = $value->path;
                $data['sort']       = $value->sort;
                $data['children']   = $value->getSubMenu();
                if(Yii::$app->controller->id == 'api/frontend'){
                    $data['content']    = $value->content;
                }
                $return[] = $data;
            }
        }
        return $return?:[];
    }


    public function getContent()
    {
        return $this->hasMany(\apiadmin\modules\models\content\Content::className(),['menu_id'=>'id'])->limit(10);
    }

    public function getMenu()
    {
        if($this->parent_id == 0){
            $data['id']         = $this->id;
            $data['label']      = $this->name;
            $data['parent_id']  = $this->parent_id;
            $data['path']       = $this->path;
            $data['sort']       = $this->sort;
            $data['children']   = $this->getSubMenu();
        }else{
            $parent             = self::findOne($this->parent_id);
            $data['id']         = $parent->id;
            $data['label']      = $parent->name;
            $data['parent_id']  = $parent->parent_id;
            $data['path']       = $parent->path;
            $data['sort']       = $parent->sort;
            $data['children']   = $parent->getSubMenu();
        }
        return $data;
    }
}
