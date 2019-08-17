<?php

use yii2extension\ml\domain\helpers\ClassifyHelper;
use yii2extension\ml\domain\helpers\NeuroTestHelper;

$training = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/spam/data/training.php');
$test = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/spam/data/test.php');

$classify = new ClassifyHelper;
$classify->train($training);
//$classify->setModel($model);
//d($classify->getModel());

// erase number, to lower case

$result = NeuroTestHelper::testClassify($test, $classify, ['spam', 'ham']);
d($result);
