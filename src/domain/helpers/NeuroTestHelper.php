<?php

namespace yii2extension\ml\domain\helpers;

use yii2extension\ml\domain\dto\TestResult;

class NeuroTestHelper {

    const OK = 'ok';
    const FAIL = 'fail';

	public static function testClassify(array $test, ClassifyHelper $classify, array $classes = []) {
        $result = [];
        $failCases = [];
        $stats = [
            self::OK => 0,
            self::FAIL => 0,
        ];
        foreach ($test as $testDocument) {
            if(count($testDocument) > 1) {
                list($expectedClass, $value) = $testDocument;
                $prediction = $classify->classify($value, $classes);
                $caseResult = [
                    $value,
                    $expectedClass,
                    $prediction,
                ];
                $result[] = $caseResult;
                $isSuccess = $prediction == $expectedClass;
                $isOk = $isSuccess ? self::OK : self::FAIL;
                $stats[$isOk]++;
                if(!$isSuccess) {
                    $failCases[] = $caseResult;
                }
            }
        }

        $stats['testCount'] = count($test);

        $totalPercent = NeuroTestHelper::getPercent($test, $stats);

        return [
            'statsPercent' => $totalPercent,
            'stats' => $stats,
            //'failCases' => $failCases,
        ];
	}

    public static function getPercent($test, $percent) : TestResult {
        $totalRate = 100 / count($test);
        $testResult = new TestResult;
        $testResult->ok = $totalRate * $percent[self::OK];
        $testResult->fail = $totalRate * $percent[self::FAIL];
        return $testResult;
    }

}
