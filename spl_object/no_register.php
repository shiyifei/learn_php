<?php

$ret = class_exists('ModelB');
echo 'before include(), class ModelB is exists:'.var_export($ret, true).'<hr/>';

include 'model/ModelA.php';
include 'model/ModelB.php';

$ret = class_exists('ModelB');
echo 'after include(), class ModelB is exists:'.var_export($ret, true).'<hr/>';

$objA = new ModelA();

$objB = new ModelB();

