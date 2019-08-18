<?php

namespace yii2extension\ml\domain\helpers;

use yii2extension\ml\domain\dto\TestResult;

class NeuroTestHelper {

    //const OK = 'ok';
    //const FAIL = 'fail';

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

    public static function ___getPercent($test, $percent) : TestResult {
        /*$totalRate = 100 / count($test);
        $testResult = new TestResult;*/

        $testResult = new TestResult;
        $testResult->ok = self::getPercentItem($test, $percent[self::OK]); // $totalRate * $percent[self::OK];
        $testResult->fail = self::getPercentItem($test, $percent[self::FAIL]); // $totalRate * $percent[self::FAIL];
        return $testResult;
    }

    public static function getPercentItem($test, $percent) {
        $totalRate = 100 / count($test);
        return $totalRate * $percent;
    }

}
