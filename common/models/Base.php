<?php
namespace common\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\web\BadRequestHttpException;

class Base extends \yii\db\ActiveRecord
{
    /**
     * 分页默认参数
     */
    const PAGE_SIZE = 10;

    public $user;
    
    public function init()
    {
        $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());
        if(isset($params['auth_key'])){
            $this->user = \apiadmin\modules\models\admin\User::loginByAuthkey($params['auth_key']);
        }
        parent::init();
    }

    /**
     * 模型查询数据
     */
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
        $find                = self::find()->filterWhere($params);
        $params['page_size'] = Yii::$app->request->post('page_size',self::PAGE_SIZE);
        $pagination   = new \yii\data\Pagination([
            'totalCount' => $find->count(),
            'pageSize'   => $params['page_size'],
        ]);
        $pagination->setPage(Yii::$app->request->post('page'-1,0),true);
        $page = $find->count();
        return $find->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
    }


    /**
     * 模型添加数据
     */
    public function insertModel()
    {
        $attributes = $this->attributes();
        $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());
        if(isset($params['id'])){
            unset($params['id']);
        }
        if($params){
            foreach ($params as $key => $value) {
                if(!in_array($key,$attributes)){
                    unset($params[$key]);
                }else{
                    $this->$key = $value;
                }   
            }
        }
        if($this->save()){
            $params['id'] = $this->id;
            return $params;
        }else{
            $error = $this->getFirstErrors();
            throw new \yii\base\InvalidValueException(reset($error));
        }
    }

    /**
     * 模型编辑数据
     */
    public function editModel()
    {
        $attributes = $this->attributes();
        $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());
        if($params){
            foreach ($params as $key => $value) {
                if(!in_array($key,$attributes)){
                    unset($params[$key]);
                }else{
                    $this->$key = $value;
                }   
            }
        }
        if(isset($params['id'])){
            $model = $this->findOne($params['id']);
            unset($params['id']);
            foreach ($params as $key => $value) {
                $model->$key = $value;
            }
            if($model->save()){
                return $params;
            }else{
                throw new \yii\base\InvalidValueException(reset($this->getErrors()));
            }
        }else{
            throw new \yii\base\InvalidValueException('数据传递错误');
        }
    }

    /**
     * 模型删除数据
     */
    public function deleteModel()
    {
        $attributes = $this->attributes();
        $params = array_merge(Yii::$app->request->post(),Yii::$app->request->get());
        if(isset($params['id'])){
            $model = $this->findOne($params['id']);
            if($model->delete()){
                return $params;
            }else{
                throw new \yii\base\InvalidValueException(reset($this->getErrors()));
            }
        }else{
            throw new \yii\base\InvalidValueException('数据传递错误');
        }
    }

    public function uploadFile($attribute)
    {
        $file  = UploadedFile::getInstanceByName($attribute);
        if ($file) {
            if ($file->error) {
                throw new BadRequestHttpException($file->error);
            }
            $file_name =  microtime(true).'.'.$file->extension;
            $file_name = $this->createUploadPath($attribute) . $file_name;
            if ($file->saveAs(Yii::getAlias('@upload').$file_name)) {
                return Yii::$app->request->getHostInfo().DIRECTORY_SEPARATOR.$file_name;
            } else {
                throw new BadRequestHttpException('文件上传失败');
            }
        } else {
            throw new BadRequestHttpException('文件上传不存在');
        }
    }

    public function createUploadPath($attribute)
    {
        $path = rtrim(DIRECTORY_SEPARATOR . 'upload'. DIRECTORY_SEPARATOR. 'banner' . DIRECTORY_SEPARATOR . $attribute . DIRECTORY_SEPARATOR);
        if (!is_dir(Yii::getAlias('@upload') . $path)) {
            FileHelper::createDirectory(Yii::getAlias('@upload') . $path, 0777);
        }
        return $path;
    }

    public function getRootPath()
    {
        return $this->root_path;
    }

    public function deleteFile($path)
    {
        @unlink($this->root_path.$path);
    }
}