<?php
namespace apiadmin\modules\v1\controllers\setting;
 
use Yii;


class BannerListController extends \apiadmin\modules\v1\controllers\BaseController
{
    
    public function actionList()
    {
        $data  = new \apiadmin\modules\models\setting\BannerList();
        return $this->setSuccess($data->queryList());
    }
    
    public function actionAdd()
    {
        $data  = new \apiadmin\modules\models\setting\BannerList();
        try {
            return $this->setSuccess($data->insertModel());
        } catch (\Exception $th) {
            return $this->setError();
        }
    }
}