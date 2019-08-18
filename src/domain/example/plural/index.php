<?php

use yii2extension\ml\domain\helpers\ClassifyHelper;
use yii2extension\ml\domain\helpers\NeuroTestHelper;
use yii2rails\extension\develop\helpers\Benchmark;

$training = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/case_of_word/data/training.php');

Benchmark::flushAll();

//$tokenizer = new \yii2extension\ml\domain\tokenizers\WhitespaceTokenizer;
$tokenizer = new \yii2extension\ml\domain\tokenizers\WhitespaceWordTokenizer;
//$tokenizer = new \NlpTools\Tokenizers\WhitespaceTokenizer();
$classify = new ClassifyHelper($tokenizer);

$trainPercentArray = [
    0.1,
    0.5,
    1,
    5,
    90,
];

$classes = ['жен', 'ср', 'муж'];

$totalResult = [];

foreach ($trainPercentArray as $trainPercent) {
    $totalResult[strval($trainPercent)] = NeuroTestHelper::testClassifyItem($classify, $training, $trainPercent, $classes);
}

//d(($classify->getModel()['condprob']));

d(\yii\helpers\ArrayHelper::map($totalResult, 'collection.train', 'test.ok'));

d($totalResult);
