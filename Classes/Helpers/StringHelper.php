<?php
namespace Classes\Helpers;

class StringHelper
{
    public static function checkMinimumChar($string, $minLength): bool
    {
        return strlen($string) >= $minLength;
    }

    public static function checkMaximumChar($string, $maxLength): bool
    {
        return strlen($string) <= $maxLength;
    }

    public static function checkLength($string, $minLength, $maxLength): bool
    {
        return self::checkMinimumChar($string, $minLength) && self::checkMaximumChar($string, $maxLength);
    }

}
