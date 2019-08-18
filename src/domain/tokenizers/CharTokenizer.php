<?php

namespace yii2extension\ml\domain\tokenizers;

use NlpTools\Tokenizers\TokenizerInterface;
use yii2rails\extension\common\enums\RegexpPatternEnum;
use yii2rails\extension\common\helpers\StringHelper;

class CharTokenizer implements TokenizerInterface
{

    const METHOD_SOLID = 'methodSolid';
    const METHOD_SPLIT = 'methodSplit';

    private $method = self::METHOD_SPLIT;

    public function __construct($method = self::METHOD_SPLIT)
    {
        $this->method = $method;
    }

    public function tokenize($str)
    {
        $str = trim($str);
        $arr = call_user_func([$this, $this->method], $str);
        return $arr;
    }

    private function methodSplit($str) {
        $c3 = mb_substr($str, -3, 1);
        $c2 = mb_substr($str, -2, 1);
        $c1 = mb_substr($str, -1, 1);
        //$arr = ['3' . $c3, '2' . $c2, '1' . $c1];
        //$arr = ['2' . $c2, '1' . $c1];
        $arr = ['1' . $c1];
        return $arr;
    }

    private function methodSolid($str) {
        $c3 = mb_substr($str, -3, 1);
        $c2 = mb_substr($str, -2, 1);
        $c1 = mb_substr($str, -1, 1);

        $arr = [
            //'1' . $c1,
            '2' . $c2,
            //'3' . $c3,
            '4' . $c2 . $c1,
            //'5' . $c3 . $c2,
            //'6' . $c3 . $c2 . $c1,
        ];
        return $arr;
    }

}
