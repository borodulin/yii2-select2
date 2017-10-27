<?php
/**
 * @link https://github.com/borodulin/yii2-select2
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-select2/blob/master/LICENSE
 */

namespace conquer\select2;

use yii\web\AssetBundle;

/**
 * Class Select2BootstrapAsset
 * @package conquer\select2
 * @link http://select2.github.io/select2-bootstrap-theme/
 * @author Andrey Borodulin
 */
class Select2BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower/select2-bootstrap-theme/dist';

    public $css = [
        'select2-bootstrap.min.css',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'conquer\select2\Select2Asset',
    ];
}