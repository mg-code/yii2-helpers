<?php
namespace mgcode\helpers;

class StringHelper extends \yii\helpers\StringHelper
{
    /**
     * Make a string's first character uppercase
     * Works with various encodings
     * @param string $str
     * @param bool $lowerEnd
     * @param string $encoding
     * @return string
     */
    public static function ucfirst($str, $lowerEnd = false, $encoding = 'UTF-8')
    {
        $firstLetter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
        if ($lowerEnd) {
            $strEnd = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
        } else {
            $strEnd = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
        }
        return $firstLetter.$strEnd;
    }

    /**
     * Generates random string
     * @param integer $length Default value 8
     * @param bool $allowUppercase Whether to allow uppercase characters
     * @return string
     */
    public static function generateRandomString($length = 8, $allowUppercase = true)
    {
        $validCharacters = 'abcdefghijklmnopqrstuxyvwz1234567890';
        if ($allowUppercase) {
            $validCharacters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $validCharNumber = strlen($validCharacters);
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $validCharNumber - 1);
            $result .= $validCharacters[$index];
        }
        return $result;
    }
}
