array-container
===========

PHP array class behave like javascript array class

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist ashrafakl/array-contanier "~1.0.0"
```

or add

```
"ashrafakl/array-contanier": "~1.0.0"
```

to the required section of your `composer.json` file.

Usage
-----

Chained method together in a single statement

```php
<?php

use ashrafakl\tools\arrays\ArrayContainer;

(new ArrayContainer([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]))
    ->map(function ($val) {
        return pow(2, $val);
    })
    ->filter(function ($val) {
        return $val > 70;
    })
    ->order(function ($list) {
        array_multisort($list, SORT_DESC, SORT_REGULAR);
        return $list;
    })
    ->unshift(5, 9)
    ->forEach(function ($value, $index) {
    echo "{$index}|{$value}" . PHP_EOL;
});

```