<?php

namespace yii2extension\ml\domain\helpers;

use yii2extension\ml\domain\dto\TestResult;
use yii2rails\extension\develop\helpers\Benchmark;

class NeuroTestHelper {

    public static function render($classify, $classes, $values) {
        $predictions = [];
        foreach ($values as $value) {
            $prediction = $classify->classify($value, $classes);
            $predictions[] = "$value = $prediction";
        }
        $content = implode(PHP_EOL, $predictions);
        d($content);
    }

    public static function testClassifyItems($classify, $train, $trainPercentArray, $classes) {
        $totalResult = [];
        foreach ($trainPercentArray as $trainPercent) {
            $totalResult[strval($trainPercent)] = NeuroTestHelper::testClassifyItem($classify, $train, $trainPercent, $classes);
        }
        return $totalResult;
    }

    public static function testClassifyItem($classify, $training, $trainPercent, $classes) {
        $collectionDto = \yii2extension\ml\domain\helpers\SplitHelper::split($training, $trainPercent);

        Benchmark::begin('train');
        $classify->train($collectionDto->train);
        Benchmark::end('train');

        Benchmark::begin('test');
        $testResultDto = NeuroTestHelper::testClassify($collectionDto->test, $classify, $classes);
        $okCount = $testResultDto->okCount;
        Benchmark::end('test');

        $benchmark = \yii\helpers\ArrayHelper::map(Benchmark::all(), 'name', 'duration');

        return [
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

	public static function testClassify(array $test, ClassifyHelper $classify, array $classes = []) : TestResult {
        $failCases = [];
        $okCount = 0;
        foreach ($test as $testDocument) {
            list($expectedClass, $value) = $testDocument;
            $prediction = $classify->classify($value, $classes);
            $caseResult = [
                $value,
                $expectedClass,
                $prediction,
            ];
            $isSuccess = $prediction == $expectedClass;
            if($isSuccess) {
                $okCount++;
            } else {
                $failCases[] = $caseResult;
            }
        }
        $testResult = new TestResult;
        $testResult->okCount = $okCount;
        $testResult->failCases = $failCases;
        return $testResult;
	}

    public static function renderItemInfo($test, $count) : string {
        return NeuroTestHelper::renderItem(NeuroTestHelper::getPercentItem($test, $count), $count);
    }

    public static function renderItem($percent, $count) : string {
        $percent = round($percent, 2);
	    return $percent . '% (' . $count . ')';
    }

    public static function getPercentItem($test, $percent) {
        $totalRate = 100 / count($test);
        return $totalRate * $percent;
    }

}
