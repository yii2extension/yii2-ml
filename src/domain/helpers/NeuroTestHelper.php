<?php

namespace yii2extension\ml\domain\helpers;

use yii2extension\ml\domain\dto\TestResult;

class NeuroTestHelper {

	public static function testClassify(array $test, ClassifyHelper $classify, array $classes = []) {
        $result = [];
        $percent = [
            true => 0,
            false => 0,
        ];
        foreach ($test as $testDocument) {
            list($expectedClass, $value) = $testDocument;
            $prediction = $classify->classify($value, $classes);
            $result[] = [
                $value,
                $expectedClass,
                $prediction,
            ];
            $isOk = $prediction == $expectedClass;
            $percent[$isOk]++;
        }
        return [
            'totalPercent' => NeuroTestHelper::getPercent($test, $percent),
            'percent' => $percent,
            'result' => $result,
        ];
	}

    public static function getPercent($test, $percent) : TestResult {
        $totalRate = count($test) / 100;
        $testResult = new TestResult;
        $testResult->ok = $percent[true] / $totalRate;
        $testResult->fail = $totalRate * $percent[false];
        return $testResult;
    }

}
