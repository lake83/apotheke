<?php
namespace app\components;

use Yii;
use yii\base\Behavior;
use app\models\Traffic;
use yii\web\Controller;

/**
 * Log all user information on request
 */
class LogTraffic extends Behavior
{
    /**
     * @var array actions that not log
     */
    public $exept;
    
    /**
     * @inheritdoc
     */
    public function events()
    {
		parent::events();
		
		return [
			Controller::EVENT_BEFORE_ACTION => 'log'
		];
	}
    
    /**
     * Save information to DB
     * 
     * @param object $event yii\base\Event
     */
    public function log($event)
    {
        $request = Yii::$app->request;
        
        if (!YII_ENV_DEV && !in_array($event->sender->action->id, $this->exept) && $request->absoluteUrl !== $request->referrer) {
            $model = new Traffic;
            $model->host = $request->absoluteUrl;
            $model->referrer = $request->referrer;
            $model->ip = $request->userIP;
            $model->agent = $request->userAgent;
            $model->cookie_id = $request->cookies['_csrf']->value;
            $model->language = implode(',', $request->acceptableLanguages);
            $model->save();
        }
    }
}