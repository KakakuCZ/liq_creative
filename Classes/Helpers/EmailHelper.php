<?php
namespace Classes\Helpers;

class EmailHelper
{
    const MAX_LENGTH = 30;

    public static function isLengthOK($email): bool
    {
        return strlen($email) <= self::MAX_LENGTH;
    }

    public static function isFormatOK($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}