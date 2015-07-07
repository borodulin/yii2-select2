<?php
/**
 * @link https://github.com/borodulin/yii2-select2
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-select2/blob/master/LICENSE
 */

namespace conquer\select2;

use yii\web\Response;
use yii\base\InvalidConfigException;

/**
 *
 * @author Andrey Borodulin
 */
class Select2Action extends \yii\base\Action
{

    /**
     * Callback function to retrieve filtered data
     * @example function ($term) { return ArrayHelper::Map(Model::find()->where(['param'=>$term]),'id','name'); }
     * @var \Closure function ($term)
     */
    public $dataCallback;
    
    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $this->controller->enableCsrfValidation = false;
    }

    public function run($term)
    {
        $request = \Yii::$app->request;

        if($this->dataCallback instanceof \Closure)
            return call_user_func($this->dataCallback, $term);        
        
        throw new InvalidConfigException('dataCallback is not configured');
    }
}