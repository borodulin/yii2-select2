<?php
/**
 * @link https://github.com/borodulin/yii2-select2
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-select2/blob/master/LICENSE
 */

namespace conquer\select2;

use yii\web\AssetBundle;

/**
 * Bootstrap 3 theme for Select2
 * @link https://github.com/select2/select2-bootstrap-theme
 * @author Andrey Borodulin
 */
class Select2BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower/select2-bootstrap-theme/dist';

    public $css = [
        'select2-bootstrap.min.css',
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
        if (class_exists('yii\bootstrap\BootstrapAsset')) {
            array_unshift($this->depends, 'yii\bootstrap\BootstrapAsset');
        }
    }
}
