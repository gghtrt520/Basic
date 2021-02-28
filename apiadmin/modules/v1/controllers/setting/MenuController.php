<?php
namespace apiadmin\modules\v1\controllers\setting;

use Yii;


class MenuController extends \apiadmin\modules\v1\controllers\BaseController
{

    /**
     * 列表展示
     */
    public function actionList()
    {
        $data  = new \apiadmin\modules\models\setting\Menu();
        return $this->setSuccess($data->searchModel());
    }

    /**
     * 菜单添加
     */
    public function actionAdd()
    {
        $data  = new \apiadmin\modules\models\setting\Menu();
        try {
            return $this->setSuccess($data->insertModel());
        } catch (\Exception $th) {
            return $this->setError();
        }
    }


    public function actionUpdate()
    {
        $data  = new \apiadmin\modules\models\setting\Menu();
        try {
            return $this->setSuccess($data->editModel());
        } catch (\Exception $th) {
            return $this->setError();
        }
    }


    public function actionDelete()
    {
        $data  = new \apiadmin\modules\models\setting\Menu();
        try {
            return $this->setSuccess($data->deleteModel());
        } catch (\Exception $th) {
            return $this->setError();
        }
    }
}