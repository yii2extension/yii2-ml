<?php

use yii2extension\ml\domain\helpers\ClassifyHelper;
use yii2extension\ml\domain\helpers\NeuroTestHelper;
use yii2rails\extension\develop\helpers\Benchmark;
use yii2extension\ml\domain\tokenizers\CharTokenizer;
use yii2rails\extension\store\StoreFile;
use yii\helpers\ArrayHelper;

$tokenizer = new CharTokenizer(CharTokenizer::METHOD_SPLIT);
$classify = new ClassifyHelper($tokenizer);
$classes = ['жен', 'ср', 'муж'];

$trainFileName = ROOT_DIR . DS . 'vendor/yii2extension/yii2-ml/src/domain/example/data/gender.csv';
$store = new StoreFile($trainFileName);
$train = $store->load();

$collectionDto = \yii2extension\ml\domain\helpers\SplitHelper::split($train, 99);
$classify->train($collectionDto->train);
//d(($classify->getModel()['condprob']));

$values = [
    'помидор',
    'помидором',
    'самолет',
    'стол',
    'селедка',
    'седло',
    'цапля',
    'петух',
    'як',
    'лань',
    'пони',
    'пингвин',
    'Щавель',
    'Эскалатор',
    'Янтарь',
];

NeuroTestHelper::render($classify, $classes, $values);

/*foreach ($values as $value) {
    $prediction = $classify->classify($classify, $classes, $values);
    d("$value = $prediction",0);
}*/

exit;