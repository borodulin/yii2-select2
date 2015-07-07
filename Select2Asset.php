<?php
/**
 * @link https://github.com/borodulin/yii2-select2
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-select2/blob/master/LICENSE
 */

namespace conquer\select2;

/**
 * @author Andrey Borodulin
 * @link https://select2.github.io/
 */
class Select2Asset extends \yii\web\AssetBundle
{
    // The files are not web directory accessible, therefore we need
    // to specify the sourcePath property. Notice the @bower alias used.
    public $sourcePath = '@bower/select2/dist';
    
    public $css=[
        'css/select2.min.css',
    ];
    
    public $js=[
        'js/select2.min.js',
    ];
    
    public $depends= [
        'yii\web\JqueryAsset',
    ];
}