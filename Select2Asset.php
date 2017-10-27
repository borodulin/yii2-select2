<?php
/**
 * @link https://github.com/borodulin/yii2-select2
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-select2/blob/master/LICENSE
 */

namespace conquer\select2;

use yii\web\AssetBundle;

/**
 * Class Select2Asset
 * @package conquer\select2
 * @link https://select2.github.io/
 * @author Andrey Borodulin
 */
class Select2Asset extends AssetBundle
{
    public $sourcePath = '@bower/select2/dist';

    public $css = [
        'css/select2.min.css',
    ];

    public $js = [
        'js/select2.full.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}