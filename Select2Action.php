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
     * Name of the GET parameter
     * @var string
     */
    public $paramName = 'q';
    
    /**
     * @var callable PHP callback function to retrieve filtered data
     * @example function ($q) { return ['results' => [['id'=>1,'text'=>'First Element'], ['id'=>2,'text'=>'Second Element']]]; }
     * @var \Closure function ($q)
     */
    public $dataCallback;
    
    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $this->controller->enableCsrfValidation = false;
    }

    public function run()
    {
        $request = \Yii::$app->request;
        
        if (!is_callable($this->dataCallback)) {
            throw new InvalidConfigException('"' . get_class($this) . '::dataCallback" should be a valid callback.');
        }

        $q = isset($_GET[$this->paramName]) ? $_GET[$this->paramName] : null;
        
        return call_user_func($this->dataCallback, $q);        
    }
}