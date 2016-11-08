<?php
/**
 * @link https://github.com/borodulin/yii2-select2
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-select2/blob/master/LICENSE
 */

namespace conquer\select2;

use yii;
use yii\helpers\Html;
use yii\helpers\Url;
use conquer\helpers\Json;

/**
 * @link https://select2.github.io
 * @author Andrey Borodulin
 */
class Select2Widget extends \yii\widgets\InputWidget
{
    /**
     * Points to use Bootstrap theme
     * @var boolean
     */
    public $bootstrap = true;
    /**
     * Language code
     * Set False to disable
     * @var string | boolean
     */
    public $language;
    /**
     * Array data
     * @example [['id'=>1, 'text'=>'enhancement'], ['id'=>2, 'text'=>'bug']]
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
     * Tagging support
     * @var boolean
     */
    public $tags;
    /**
     * @link https://select2.github.io/options.html
     * @var array
     */
    public $settings = [];
    
    /**
     * If value is integer, then it passed as "cushion" parameter 
     * @link https://github.com/panorama-ed/maximize-select2-height
     * @var mixed
     */
    public $maximize = false;
    
    /**
     * @var string[] the JavaScript event handlers.
     */
    public $events = array();

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if ($this->tags) {
            $this->options['data-tags'] = 'true';
            $this->options['multiple'] = true;
        }
        if ($this->language) {
            $this->options['data-language'] = $this->language;
        }
        if (!is_null($this->ajax)) {
            $this->options['data-ajax--url'] = Url::to($this->ajax);
            $this->options['data-ajax--cache'] = 'true';
        }
        if ($this->placeholder) {
            $this->options['data-placeholder'] = $this->placeholder;
        }
        if ($this->multiple) {
            $this->options['data-multiple'] = 'true';
            $this->options['multiple'] = true;
        }
        if (!empty($this->data)) {
            $this->options['data-data'] = \yii\helpers\Json::encode($this->data);
        }
        if (!isset($this->options['class'])) {
            $this->options['class'] = 'form-control';
        }
        if ($this->bootstrap) {
            $this->options['data-theme'] = 'bootstrap';
        }
        if ($this->multiple || !empty($this->settings['multiple'])) {
            if ($this->hasModel()) {
                $name = isset($this->options['name']) ? $this->options['name'] : Html::getInputName($this->model, $this->attribute);
            } else {
                $name = $this->name;
            }
            if (substr($name, -2) != '[]') {
                $this->options['name'] = $this->name = $name . '[]';
            }
        }
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
        /* @var $bandle yii\web\AssetBundle */
        $bandle = Select2Asset::register($view);
        if ($this->language !== false) {
            $langs[0] = $this->language ? $this->language : \Yii::$app->language;
            if (($pos = strpos($langs[0], '-')) > 0) {
                // If "en-us" is not found, try to use "en".
                $langs[1] = substr($langs[0], 0, $pos);
            }
            foreach ($langs as $lang) {
                $langFile = "/js/i18n/{$lang}.js";
                if (file_exists($bandle->sourcePath . $langFile)) {
                    $view->registerJsFile($bandle->baseUrl . $langFile, ['depends' => Select2Asset::className()]);
                    break;
                }
            }
        }
        if ($this->bootstrap) {
            Select2BootstrapAsset::register($view);
        }
        $settings = Json::encode($this->settings);
        $js = "jQuery('#{$this->options['id']}').select2($settings)";
        if ($this->maximize) {
            Select2MaximizeAsset::register($view);
            if (is_integer($this->maximize)) {
                $this->maximize = "{cushion: $this->maximize}";
            } elseif (is_array($this->maximize)) {
                $this->maximize = Json::encode($this->maximize);
            } else {
                $this->maximize = '{}';
            }
            $js .= ".maximizeSelect2Height($this->maximize)";
        }
        foreach ($this->events as $event => $handler) {
            $js .= '.on("'.$event.'", ' . new yii\web\JsExpression($handler) . ')';
        }
        $view->registerJs("$js;");
    }
}