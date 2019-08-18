<?php

use yii2extension\ml\domain\helpers\ClassifyHelper;
use yii2extension\ml\domain\helpers\NeuroTestHelper;
use yii2rails\extension\develop\helpers\Benchmark;

$training = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/spam/data/training.php');
$model = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/spam/data/model.php');
//$test = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/spam/data/test.php');

$rr = \yii2extension\ml\domain\helpers\SplitHelper::split($training, 99);

$training = $rr->train;
$test = $rr->test;

Benchmark::flushAll();

$classify = new ClassifyHelper;
Benchmark::begin('train');
$classify->train($training);
Benchmark::end('train');
//$classify->setModel($model);
//d($classify->getModel());

// erase number, to lower case

Benchmark::begin('test');
$result = NeuroTestHelper::testClassify($test, $classify, ['spam', 'ham']);
Benchmark::end('test');

$result['stats']['trainingCount'] = count($training);
$result['stats']['totalCount'] = $result['stats']['trainingCount'] + $result['stats']['testCount'];
$result['benchmark'] = \yii\helpers\ArrayHelper::map(Benchmark::all(), 'name', 'duration');
d($result);
