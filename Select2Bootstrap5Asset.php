<?php
/**
 * @link https://github.com/borodulin/yii2-select2
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-select2/blob/master/LICENSE
 */

namespace conquer\select2;

use yii\web\AssetBundle;

/**
 * Bootstrap 5 theme for Select2
 * @link https://github.com/apalfrey/select2-bootstrap-5-theme
 * @author Andrey Borodulin
 */
class Select2Bootstrap5Asset extends AssetBundle
{
    public $sourcePath = '@npm/select2-bootstrap-5-theme/dist';

    public $css = [
        'select2-bootstrap-5-theme.min.css',
    ];

    public $depends = [
        'conquer\select2\Select2Asset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // load after Bootstrap when the Yii Bootstrap extension is used
        if (class_exists('yii\bootstrap5\BootstrapAsset')) {
            array_unshift($this->depends, 'yii\bootstrap5\BootstrapAsset');
        }
    }
}
