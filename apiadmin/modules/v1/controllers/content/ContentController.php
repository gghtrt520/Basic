<?php
namespace apiadmin\modules\v1\controllers\content;
 
use Yii;


class ContentController extends \apiadmin\modules\v1\controllers\BaseController
{

    public function actionList()
    {
        $data  = new \apiadmin\modules\models\content\Content();
        return $this->setSuccess($data->searchModel($this->pages),$this->getPages());
    }

    public function actionAdd()
    {
        $data  = new \apiadmin\modules\models\content\Content();
        try {
            return $this->setSuccess($data->insertModel());
        } catch (\Exception $th) {
            return $this->setError();
        }
    }

    public function actionUpdate()
    {
        $data  = new \apiadmin\modules\models\content\Content();
        try {
            return $this->setSuccess($data->editModel());
        } catch (\Exception $th) {
            return $this->setError();
        }
    }


    public function actionDelete()
    {
        $data  = new \apiadmin\modules\models\content\Content();
        try {
            return $this->setSuccess($data->deleteModel());
        } catch (\Exception $th) {
            return $this->setError();
        }
    }
}