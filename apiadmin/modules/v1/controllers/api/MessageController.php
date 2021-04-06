<?php
namespace apiadmin\modules\v1\controllers\api;
 
use Yii;


class MessageController extends \apiadmin\modules\v1\controllers\BaseController
{
    public function actionAdd()
    {
        $data  = new \apiadmin\modules\models\content\Message();
        try {
            return $this->setSuccess($data->insertModel());
        } catch (\Exception $th) {
            print_r($th->getMessage());die;
            return $this->setError();
        }
    }
}