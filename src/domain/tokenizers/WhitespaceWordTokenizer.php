<?php

namespace yii2extension\ml\domain\tokenizers;

use NlpTools\Tokenizers\TokenizerInterface;
use yii2rails\extension\common\enums\RegexpPatternEnum;
use yii2rails\extension\common\helpers\StringHelper;

/**
 * Simple white space tokenizer.
 * Break on every white space
 */
class WhitespaceWordTokenizer implements TokenizerInterface
{
    const PATTERN = '/[\pZ\pC]+/u';

    public function tokenize($str)
    {
        $str = $this->cleanText($str);
        $str = mb_substr($str, -2);
        $str2 = mb_substr($str, -1);
        $arr = [/*$str,*/ $str2];
        //$arr = mb_str_split($str, 2);

        //d($arr);
        //$arr = array_unique($arr);
        return $arr;
    }

    private function cleanText($str) {
        $str = mb_strtolower($str);
        //$str = preg_replace('#[^а-яёa-z0-9]+#mu', SPC, $str);
        $str = preg_replace('#[^[:alnum:]]+#mu', SPC, $str);
        $str = preg_replace('#[0-9]{1}#mu', '9', $str);
        $str = StringHelper::removeDoubleSpace($str);
        return $str;
    }

}
