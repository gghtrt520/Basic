<?php
namespace apiadmin\modules\v1\controllers\api;
 
use Yii;


class FrontendController extends \apiadmin\modules\v1\controllers\BaseController
{

    public function actionList()
    {
        $data  = new \apiadmin\modules\models\setting\Menu();
        return $this->setSuccess($data->searchModel($this->pages),$this->getPages());
    }
}