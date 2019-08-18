<?php

use yii2extension\ml\domain\helpers\ClassifyHelper;
use yii2extension\ml\domain\helpers\NeuroTestHelper;
use yii2rails\extension\develop\helpers\Benchmark;

$training = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/spam/data/training.php');
$model = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/spam/data/model.php');
//$test = include(ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/spam/data/test.php');

Benchmark::flushAll();

$tokenizer = new \yii2extension\ml\domain\tokenizers\WhitespaceTokenizer;
//$tokenizer = new \NlpTools\Tokenizers\WhitespaceTokenizer();
$classify = new ClassifyHelper($tokenizer);

$trainPercentArray = [
    0.1,
    0.5,
    1,
    5,
    //99
];
//$trainPercent = 5;

$totalResult = [];
$totalTable = [];

foreach ($trainPercentArray as $trainPercent) {
    $collectionDto = \yii2extension\ml\domain\helpers\SplitHelper::split($training, $trainPercent);

    Benchmark::begin('train');
    $classify->train($collectionDto->train);
    Benchmark::end('train');

    //d(array_keys($classify->getModel()['condprob']));

    Benchmark::begin('test');
    $testResultDto = NeuroTestHelper::testClassify($collectionDto->test, $classify, ['spam', 'ham']);
    $okCount = $testResultDto->okCount;
    Benchmark::end('test');

    $benchmark = \yii\helpers\ArrayHelper::map(Benchmark::all(), 'name', 'duration');

    $totalTable[] = [
        'trainPercent' => $trainPercent,
        'okCountPercent' => NeuroTestHelper::renderItemInfo($collectionDto->test, $okCount),
    ];

    $totalResult[] = [
        'collection' => [
            'total' => NeuroTestHelper::renderItem(100, count($collectionDto->all)),
            'train' => NeuroTestHelper::renderItem($trainPercent, count($collectionDto->train)),
            'test' => NeuroTestHelper::renderItem(100 - $trainPercent, count($collectionDto->test)),
        ],
        'test' => [
            'ok' => NeuroTestHelper::renderItemInfo($collectionDto->test, $okCount),
            'fail' => NeuroTestHelper::renderItemInfo($collectionDto->test, count($collectionDto->test) - $okCount),
        ],
        'benchmark' => [
            'train' => round($benchmark['train'], 4) . ' sec.',
            'test' => round($benchmark['test'], 4) . ' sec.',
        ],
    ];

}

d($totalTable);
d($totalResult);

/*if($trainPercent) {
    $collection = \yii2extension\ml\domain\helpers\SplitHelper::split($training, $trainPercent);
    $training = $collection->train;
    $test = $collection->test;
    Benchmark::begin('train');
    $classify->train($training);
    Benchmark::end('train');
} else {
    Benchmark::begin('set_model');
    $classify->setModel($model);
    Benchmark::end('set_model');
}

//d($classify->getModel());

// erase number, to lower case

Benchmark::begin('test');
$result = NeuroTestHelper::testClassify($test, $classify, ['spam', 'ham']);
Benchmark::end('test');

$result['stats']['trainingCount'] = count($training);
$result['stats']['totalCount'] = $result['stats']['trainingCount'] + $result['stats']['testCount'];
$result['benchmark'] = \yii\helpers\ArrayHelper::map(Benchmark::all(), 'name', 'duration');
d($result);
*/