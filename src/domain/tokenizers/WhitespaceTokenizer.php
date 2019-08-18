<?php

namespace yii2extension\ml\domain\tokenizers;

use NlpTools\Tokenizers\TokenizerInterface;
use yii2rails\extension\common\enums\RegexpPatternEnum;
use yii2rails\extension\common\helpers\StringHelper;

/**
 * Simple white space tokenizer.
 * Break on every white space
 */
class WhitespaceTokenizer implements TokenizerInterface
{
    const PATTERN = '/[\pZ\pC]+/u';

    public function tokenize($str)
    {
        $str = $this->cleanText($str);
        $arr = preg_split(self::PATTERN,$str,null,PREG_SPLIT_NO_EMPTY);
        $arr = array_unique($arr);
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
