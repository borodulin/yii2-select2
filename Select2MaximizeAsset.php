<?php
/**
 * @link https://github.com/borodulin/yii2-select2
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-select2/blob/master/LICENSE
 */

namespace conquer\select2;

/**
 * https://github.com/panorama-ed/maximize-select2-height
 */
class Select2MaximizeAsset extends \yii\web\AssetBundle
{
    // The files are not web directory accessible, therefore we need
    // to specify the sourcePath property. Notice the @conquer alias used.
    public $sourcePath = '@conquer/select2/assets';
    
    public $js=[
        'maximize-select2-height.min.js',
    ];
    
    public $depends= [
        'conquer\select2\Select2Asset',
    ];
}