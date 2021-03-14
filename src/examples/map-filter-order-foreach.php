<?php

use ashrafakl\tools\arrays\ArrayContainer;

$array = (new ArrayContainer([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]))
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
    ->unshift(5, 9);
$array->forEach(function ($value, $index) {
    echo "{$index}|{$value}" . PHP_EOL;
});
