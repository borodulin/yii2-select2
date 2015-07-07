<?php
/**
 * @link https://github.com/borodulin/yii2-select2
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-select2/blob/master/LICENSE
 */

namespace conquer\select2;

use yii\helpers\Html;
use conquer\helpers\Json;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
/**
 * @link https://select2.github.io
 * @author Andrey Borodulin
 */
class Select2Widget extends \yii\widgets\InputWidget
{
    /**
     * Language code
     * @var string
     */
    public $language;
    /**
     * Array data
     * @example [['id'=> 0, 'text'=> 'enhancement'], ['id'=>  1, 'text'=> 'bug']]
     * @var array
     */
    public $data;
    /**
     * You can use Select2Action to provide AJAX data
     * @see \yii\helpers\BaseUrl::to()
     * @var array|string
     */
    public $ajax;
    /**
     * 
     * @see \yii\helpers\BaseArrayHelper::map()
     * @var array
     */
    public $items = [];
    /**
     * A placeholder value can be defined and will be displayed until a selection is made
     * @var string
     */
    public $placeholder;
    /**
     * Multiple select boxes
     * @var boolean
     */
    public $multiple;
    /**
     * @link https://select2.github.io/options.html
     * @var array
     */
    public $settings;
    
    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        parent::init();
        
        if(empty($this->items)||empty($this->data)||empty($this->ajax))
            throw new InvalidConfigException('You need to configute one of the data sources');
        
        if(isset($this->tags))
            $this->options['data-tags'] = $this->tags;
        if(isset($this->language))
            $this->options['data-language'] = $this->language;
        if(isset($this->ajax)){
            $this->options['data-ajax--url'] = $this->ajax;
            $this->options['data-ajax--cache'] = true;
        }
        if(isset($this->placeholder))
            $this->options['data-placeholder'] = $this->placeholder;
        if(isset($this->multiple))
            $this->options['data-multiple'] = $this->multiple;
        if(isset($this->data))
            $this->options['data-data'] = Json::encode($this->data);
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeDropDownList($this->model, $this->attribute, $this->items, $this->options);
        } else {
            echo Html::dropDownList($this->name, $this->value, $this->items, $this->options);
        }
        $this->registerAssets();
    }
    
    /**
     * Registers Assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        
        Select2Asset::register($view);
        
        $id = $this->options['id'];
       
        $settings = Json::encode($this->settings);
        $js = "jQuery('$id').select2($settings);";
        $view->registerJs($js);
    }
    
}