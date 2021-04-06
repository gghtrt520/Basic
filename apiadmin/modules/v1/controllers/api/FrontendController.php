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

    public function actionContent()
    {
        $id = Yii::$app->request->get('id');
        if($content = \apiadmin\modules\models\content\Content::findOne($id)){
            $content->updateCounters(['times' => 1]);
            return $this->setSuccess($content);
        }else {
            return $this->setError('数据未找到');
        }
    }

    public function actionDetail()
    {
        $menu_id = Yii::$app->request->post('menu_id');
        $menu    = \apiadmin\modules\models\setting\Menu::findOne($menu_id);
        if($menu){
            $data['menu'] = $menu->getMenu();
            $content      = new  \apiadmin\modules\models\content\Content();
            $data['content'] = $content->searchModel($this->pages);
            return $this->setSuccess($data);
        }else {
            return $this->setError('数据未找到');
        }

    }
}