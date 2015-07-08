Select2 widget for Yii2 framework
=================

## Description

Select2 gives you a customizable select box with support for searching, tagging, remote data sets, infinite scrolling, and many other highly used options.
For more information please visit [Select2](https://select2.github.io/) 

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/). 

To install, either run

```
$ php composer.phar require conquer/select2 "*"
```
or add

```
"conquer/select2": "*"
```

to the ```require``` section of your `composer.json` file.

## Usage

Basic usage:

```php
// Form edit view
use conquer\select2\Select2Widget;
use yii\helpers\ArrayHelper;

$form->field($model, 'attribute')->widget(
    Select2::className(),
    [
        'items'=>ArrayHelper::map(Catalog::find()->all(), 'id', 'name')
    ]
);
```

Ajax:

```php

use conquer\select2\Select2Action;
...

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'ajax' => [
                'class' => Select2Action::className(),
                'dataCallback' => [$this, 'dataCallback'],
            ],
        ];
    }
    /**
     * 
     * @param string $q
     * @return array
     */
    public function dataCallback($q)
    {
        $query = new ActiveQuery(Catalog::className());
        return [
            'results' =>  $query->select([
                    'catalog_id as id',
                    'catalog_name as text', 
                ])
                ->filterWhere(['like', 'catalog_name', $q])
                ->asArray()
                ->limit(20)
                ->all(),
        ];
    }
}

// Form edit view:

$form->field($model, 'attribute')->widget(
    Select2::className(),
    [
        'ajax' => ['site/ajax']
    ]
);
```

## License

**conquer/select2** is released under the MIT License. See the bundled `LICENSE` for details.
