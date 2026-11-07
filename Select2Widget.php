<?php
/**
 * @link https://github.com/borodulin/yii2-select2
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-select2/blob/master/LICENSE
 */

namespace conquer\select2;

use conquer\helpers\Json;
use yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json as DataJson;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * Class Select2Widget
 * @package conquer\select2
 * @link https://select2.github.io
 * @author Andrey Borodulin
 */
class Select2Widget extends InputWidget
{
    /**
     * @var array Bootstrap major version => [theme asset bundle, Select2 theme name, theme package, Yii extension namespace]
     */
    public static $bootstrapThemes = [
        5 => [Select2Bootstrap5Asset::class, 'bootstrap-5', 'npm-asset/select2-bootstrap-5-theme', 'yii\bootstrap5'],
        4 => [Select2Bootstrap4Asset::class, 'bootstrap4', 'npm-asset/ttskch--select2-bootstrap4-theme', 'yii\bootstrap4'],
        3 => [Select2BootstrapAsset::class, 'bootstrap', 'bower-asset/select2-bootstrap-theme', 'yii\bootstrap'],
    ];

    /**
     * Use the Bootstrap theme of [[bsVersion]].
     * null (default) enables it when the theme package is installed,
     * true requires it, false disables the theme.
     * @var boolean|null
     */
    public $bootstrap;

    /**
     * Bootstrap major version (3, 4 or 5) of the theme.
     * null (default): the newest installed Yii Bootstrap extension whose theme package is installed,
     * otherwise the newest installed Yii Bootstrap extension (3 when none is installed).
     * @var int|null
     */
    public $bsVersion;

    /**
     * Language code
     * Set False to disable
     * @var string | boolean
     */
    public $language;

    /**
     * Array data
     * @example [['id'=>1, 'text'=>'enhancement'], ['id'=>2, 'text'=>'bug']]
     * Encoded as plain JSON: strings with the "js:" prefix are not turned into JavaScript.
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
    public $events = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->bootstrap = $this->resolveBootstrap();
        $this->_langFile = $this->resolveLanguageFile();

        if ($this->tags) {
            $this->options['data-tags'] = 'true';
            $this->options['multiple'] = true;
        }
        if ($this->language) {
            $this->options['data-language'] = $this->language;
        } elseif ($this->_langFile !== null) {
            // otherwise Select2 takes the language from <html lang> only
            $this->options['data-language'] = basename($this->_langFile, '.js');
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
            $this->options['data-data'] = DataJson::encode($this->data);
        }
        if (!isset($this->options['class'])) {
            $this->options['class'] = 'form-control';
        }
        if ($this->bootstrap) {
            $this->options['data-theme'] = static::$bootstrapThemes[$this->bsVersion][1];
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
     * @var string|null path of the Select2 translation file relative to the Select2 dist directory
     */
    private $_langFile;

    /**
     * Resolves [[bsVersion]].
     * @return bool whether the Bootstrap theme is used
     * @throws InvalidConfigException
     */
    protected function resolveBootstrap()
    {
        if ($this->bsVersion === null) {
            $this->bsVersion = static::detectBsVersion($this->bootstrap !== false);
        } elseif (!isset(static::$bootstrapThemes[(int)$this->bsVersion])) {
            throw new InvalidConfigException('"bsVersion" must be 3, 4 or 5.');
        }
        $this->bsVersion = (int)$this->bsVersion;
        if ($this->bootstrap === false) {
            return false;
        }
        $available = static::isThemeInstalled($this->bsVersion);
        if ($this->bootstrap && !$available) {
            throw new InvalidConfigException('The Bootstrap ' . $this->bsVersion . ' theme requires "'
                . static::$bootstrapThemes[$this->bsVersion][2] . '". Install it or set "bootstrap" to false.');
        }
        return $available;
    }

    /**
     * @param bool $preferTheme prefer a version whose theme package is installed
     * @return int Bootstrap major version
     */
    public static function detectBsVersion($preferTheme = true)
    {
        $installed = [];
        foreach (static::$bootstrapThemes as $version => $theme) {
            if (class_exists($theme[3] . '\BootstrapAsset')) {
                if ($preferTheme && static::isThemeInstalled($version)) {
                    return $version;
                }
                $installed[] = $version;
            }
        }
        return $installed ? $installed[0] : 3;
    }

    /**
     * @param int $version Bootstrap major version
     * @return bool whether the theme package for the version is installed
     */
    public static function isThemeInstalled($version)
    {
        static $installed = [];
        $class = static::$bootstrapThemes[$version][0];
        if (!isset($installed[$class])) {
            /** @var yii\web\AssetBundle $bundle */
            $bundle = Yii::createObject($class);
            $path = Yii::getAlias($bundle->sourcePath, false);
            $installed[$class] = $path && is_file($path . '/' . $bundle->css[0]);
        }
        return $installed[$class];
    }

    /**
     * @return string|null the translation file for [[language]] or the application language
     */
    protected function resolveLanguageFile()
    {
        if ($this->language === false) {
            return null;
        }
        $lang = $this->language ? $this->language : Yii::$app->language;
        $langs = [$lang];
        if (($pos = strpos($lang, '-')) > 0) {
            // If "en-us" is not found, try to use "en".
            $langs[] = substr($lang, 0, $pos);
        }
        $dist = Yii::getAlias((new Select2Asset())->sourcePath, false);
        foreach ($langs as $lang) {
            if (preg_match('/^[a-zA-Z]{2,3}(-[a-zA-Z0-9]+)*$/', $lang) && $dist && is_file("$dist/js/i18n/$lang.js")) {
                return "js/i18n/$lang.js";
            }
        }
        return null;
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
        /** @var yii\web\AssetBundle $bandle */
        $bandle = Select2Asset::register($view);
        if ($this->_langFile !== null) {
            $view->registerJsFile($bandle->baseUrl . '/' . $this->_langFile, ['depends' => Select2Asset::class]);
        }
        if ($this->bootstrap) {
            $view->registerAssetBundle(static::$bootstrapThemes[$this->bsVersion][0]);
        }
        $settings = empty($this->settings) ? '{}' : Json::encode($this->settings);
        $js = 'jQuery(document.getElementById(' . DataJson::htmlEncode((string)$this->options['id']) . ")).select2($settings)";
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
            $js .= '.on("' . $event . '", ' . new JsExpression($handler) . ')';
        }
        $view->registerJs("$js;");
    }
}