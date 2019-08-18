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
        return $arr;
    }

    private function cleanText($str) {
        $str = preg_replace('#[^а-яА-ЯёЁa-zA-Z0-9]+#m', SPC, $str);
        $str = preg_replace('#[0-9]{1}#m', '9', $str);
        $str = StringHelper::removeDoubleSpace($str);
        $str = mb_strtolower($str);
        return $str;
    }

}
