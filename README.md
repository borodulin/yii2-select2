# Select2 widget for Yii2 framework

[![Latest Version](https://img.shields.io/packagist/v/conquer/select2.svg)](https://packagist.org/packages/conquer/select2)
[![Total Downloads](https://img.shields.io/packagist/dt/conquer/select2.svg)](https://packagist.org/packages/conquer/select2)
[![License](https://img.shields.io/packagist/l/conquer/select2.svg)](LICENSE)

## Description

Select2 gives you a customizable select box with support for searching, tagging, remote data sets, infinite scrolling, and many other highly used options.
For more information please visit [Select2](https://select2.github.io/) 

## Requirements

- PHP 7.4 or 8.x
- Yii 2.0.6 or newer (2.0.x)
- [asset-packagist](https://asset-packagist.org) repository configured in your application's `composer.json` (standard for Yii2 app templates)

## Installation

Install the extension via [Composer](https://getcomposer.org):

```bash
composer require conquer/select2:^1.7
```

## Usage

Basic usage:

```php
// Form edit view
use conquer\select2\Select2Widget;
use yii\helpers\ArrayHelper;

$form->field($model, 'attribute')->widget(
    Select2Widget::class,
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
                'class' => Select2Action::class,
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
        $query = new ActiveQuery(Catalog::class);
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
    Select2Widget::class,
    [
        'ajax' => ['site/ajax']
    ]
);
```

Jquery Events:

Array the Select2 JQuery events. You must define events in event-name => event-function format. All events will be stacked in the sequence. Refer the [plugin options documentation ](https://select2.github.io/options.html) for details. 

For example:

```php

$form->field($model, 'attribute')->widget(
    Select2Widget::class,
    [
        'events' => [
            'select2:open' => "function() { log('open'); }",
        ]
    ]
);

```

Initialization of multiple selection in case of using ajax and custom templates.

```php
<?= $form->field($model, 'multipleItems')->widget(Select2Widget::class, [
    'options' => [
        'placeholder' => 'Select items ...',
    ],
    'ajax' => Url::to(['items/search']),
    'multiple' => true,
    'items' => ArrayHelper::map($model->multipleItems, 'id', 'text'),
    // Initial data the same, as returned results from Ajax request items/search
    'data' => $model->multipleItems,
    'settings' => [
        'ajax' => ['delay' => 250],
        'minimumInputLength' => 1,
        'minimumResultsForSearch' => -1,
        /** 
         * Handlebars here is used as example of using template engine
         * If you will not provide initial data,
         *   custom templates will not access additional info of items
         */
        'templateResult' => 'js:Handlebars.compile($("#template-result").html())',
        'templateSelection' => 'js:Handlebars.compile($("#template-selection").html())',
        'escapeMarkup' => 'js:function(markup){ return markup; }',
    ],
]) ?>
```

> **Security:** with `escapeMarkup` turned off, as above, the templates are responsible for escaping.
> Use only escaping template tags (`{{ }}` in Handlebars, not `{{{ }}}`) for data that comes from users.
> `data` is encoded as plain JSON, `settings` support the `js:` prefix: never put user data into `settings`.

## Bootstrap theme

The [select2-bootstrap-theme](https://github.com/select2/select2-bootstrap-theme) (Bootstrap 3) is used automatically
when it is installed together with `yiisoft/yii2-bootstrap`. It is no longer a dependency of this package
(it has only beta releases, which broke installation in projects with `"minimum-stability": "stable"`):

```bash
composer require yiisoft/yii2-bootstrap:~2.0 "bower-asset/select2-bootstrap-theme:^0.1@beta"
```

Set `'bootstrap' => false` to disable the theme, `'bootstrap' => true` to require it.

## License

**conquer/select2** is released under the MIT License. See the bundled [`LICENSE`](LICENSE) for details.
