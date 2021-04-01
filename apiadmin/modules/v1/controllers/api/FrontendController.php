<?php
namespace apiadmin\modules\v1\controllers\api;
 
use Yii;


class FrontendController extends \apiadmin\modules\v1\controllers\BaseController
{

    public function beforeAction($action)
    {
        $record = \apiadmin\modules\models\setting\Record::findOne(['date'=>date('Y-m-d')]);
        if($record){
            $record->updateCounters(['times' => 1]);
        }else{
            $record = new \apiadmin\modules\models\setting\Record();
            $record->date  = date('Y-m-d');
            $record->times = 1;
            $record->save();
        }
        return true;
    }

    public function actionList()
    {
        $data  = new \apiadmin\modules\models\setting\Menu();
        $extend  = [
            'times'  => \apiadmin\modules\models\setting\Record::getAll(),
            'link'   => \apiadmin\modules\models\setting\LinkCategory::getAll(),
            'banner' => \apiadmin\modules\models\setting\LoopBanner::getAll()
        ];
        return $this->setSuccess($data->searchModel($this->pages),$extend);
    }
}