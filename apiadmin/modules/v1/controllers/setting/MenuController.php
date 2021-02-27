<?php
namespace apiadmin\modules\v1\controllers\setting;

use Yii;


class MenuController extends \apiadmin\modules\v1\controllers\BaseController
{
    public function actionList()
    {
        $data  = new \apiadmin\modules\models\setting\Menu();
        return $this->setSuccess($data->queryList());
    }

    public function actionAdd()
    {
        $data  = new \apiadmin\modules\models\setting\Menu();
        try {
            return $this->setSuccess($data->insertModel());
        } catch (\Exception $th) {
            return $this->setError();
        }
    }
}