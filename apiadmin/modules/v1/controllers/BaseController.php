<?php
namespace apiadmin\modules\v1\controllers;

use Yii;
use yii\web\Response;
use common\models\DB;
use apiadmin\modules\models\admin\User;
 

class BaseController extends \yii\web\Controller
{
    public  $pages  = 0;
    private $http_id;
    public  $post;
    public  $get;
    public  $user;  //用户信息
    public  $uid;
    public  $request;
    public  $db;

    
    public function beforeAction($action)
    {
        $this->request = array_merge(Yii::$app->request->post(),Yii::$app->request->get(),$_FILES); 
        $this->http_id = DB::insert('http_log', [
            'method'      => Yii::$app->request->getMethod(),
            'full_url'    => Yii::$app->request->absoluteUrl,
            'http_status' => Yii::$app->response->statusCode,
            'ips'         => Yii::$app->request->userIP,
            'request'     => json_encode($this->request, JSON_UNESCAPED_UNICODE),
            'create_at'   => date("Y-m-d H:i:s")
        ]);
        return true;
    }

    public function getPages()
    {
        return ['pages'=>$this->pages];
    }


    public function setSuccess($send=[],$extend=[])
    {
        $data = [
            'status' => 200,
            'code'   => 0,
            'msg'    => '操作成功',
            'data'   => $send,
            'extend' => $extend 
        ];
        DB::update('http_log', [ 'http_status' => $data['status'], 'response' => json_encode($send,JSON_UNESCAPED_UNICODE),'finish_at'=>date("Y-m-d H:i:s") ], ['id'=>$this->http_id]);
        return $this->asJson($data);
    }

    
    public function setError($error='')
    {
        $data = [
            'status' => 400,
            'code'   => 1,
            'msg'    => $error?:'操作失败',
            'data'   => [],
            'extend' => [] 
        ];
        DB::update('http_log', [ 'http_status' => $data['status'], 'response' => json_encode([],JSON_UNESCAPED_UNICODE),'finish_at'=>date("Y-m-d H:i:s") ], ['id'=>$this->http_id]);
        return $this->asJson($data);
    }
    
    public function getError($errors)
    {
        return reset($errors);
    }



    //记入日志
    public function write_log($content,$tempDir='')
    {
        if(is_array($content) || is_object($content))
            $content = json_encode($content,JSON_UNESCAPED_UNICODE);
        
        $content = "[".date('Y-m-d H:i:s')."]".$content."\r\n";
        $dir = rtrim(str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']),'/').'/logs';
        if($tempDir) $dir .= '/'.$tempDir;
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        $path = $dir.'/'.date('Y-m-d').'.txt';
        file_put_contents($path,$content,FILE_APPEND);
    }   

    public function actionUpload()
    {
        $model = new \common\models\Base();
        try {
            $path = $model->uploadFile('file');
            return $this->setSuccess($path);
        } catch (\Exception $th) {
            print_r($th->getMessage());
            return $this->setError();
        }
    }
    
}