<?php
namespace apiadmin\modules\v1\controllers\setting; 
use apiadmin\modules\v1\controllers\CoreController;
use Yii;


class MenuController extends \apiadmin\modules\v1\controllers\BaseController
{
    public function actionList()
    {
        $data  = new \apiadmin\modules\models\setting\Menu();
        return $this->setSuccess($data->queryList());
        $this->out('菜单列表',$data->queryList());
    }
}