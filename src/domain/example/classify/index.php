<?php

use yii2extension\ml\domain\helpers\ClassifyHelper;
use yii2extension\ml\domain\helpers\NeuroTestHelper;

$training = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/classify/data/training.php');
$test = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/classify/data/test.php');
$expected = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/classify/data/expected.php');
$model = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/classify/data/model.php');

$classify = new ClassifyHelper;
//$classify->train($training);
$classify->setModel($model);

$result = NeuroTestHelper::testClassify($test, $classify);
d($result == $expected, 0);
d($result);
