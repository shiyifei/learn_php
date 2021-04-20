<?php

function whereBetween($column, array $values, $boolean = 'and', $not = false)
{
    $type = 'between';
    $wheres = [];

    $wheres[] = compact('type', 'column', 'values', 'boolean', 'not');

    var_dump($wheres);

    return $wheres;

}


$ret = whereBetween('createtime', ['2019-11-27 00:00:00', '2019-11-27 23:59:59']);


$output = new Collection($ret);

var_dump($output);





