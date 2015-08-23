<?php
/**
 * @link https://github.com/borodulin/yii2-select2
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-select2/blob/master/LICENSE
 */

namespace conquer\select2;

/**
 * @author Andrey Borodulin
 * @link http://select2.github.io/select2-bootstrap-theme/
 */
class Select2BootstrapAsset extends \yii\web\AssetBundle
{
    // The files are not web directory accessible, therefore we need
    // to specify the sourcePath property. Notice the @bower alias used.
    public $sourcePath = '@conquer/select2/assets';
    
    public $css=[
        'select2-bootstrap.min.css',
    ];
    
    public $depends= [
        'yii\bootstrap\BootstrapAsset',
        'conquer\select2\Select2Asset',
    ];
}