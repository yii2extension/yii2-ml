<?php

use yii2extension\ml\domain\helpers\ClassifyHelper;
use yii2extension\ml\domain\helpers\NeuroTestHelper;
use yii2rails\extension\develop\helpers\Benchmark;
use yii2extension\ml\domain\tokenizers\CharTokenizer;
use yii2rails\extension\store\StoreFile;
use yii\helpers\ArrayHelper;

$tokenizer = new CharTokenizer(CharTokenizer::METHOD_SOLID);
$classify = new ClassifyHelper($tokenizer);
$classes = [0, 1];

$trainFileName = ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/data/plural.csv';
$store = new StoreFile($trainFileName);
$train = $store->load();

Benchmark::flushAll();

$trainPercentArray = [
    0.1,
    0.5,
    1,
    5,
    90,
];

$totalResult = NeuroTestHelper::testClassifyItems($classify, $train, $trainPercentArray, $classes);

//d(($classify->getModel()['condprob']));

d(ArrayHelper::map($totalResult, 'collection.train', 'test.ok'));

d($totalResult);
