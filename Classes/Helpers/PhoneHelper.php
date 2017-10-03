<?php
namespace Helpers;

class PhoneHelper
{
    public static function isFormatOK($phoneNumber)
    {
        return preg_match(
            '^(((\+44\s?\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\+44\s?\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\+44\s?\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\#(\d{4}|\d{3}))?$',
            $phoneNumber);
    }
}
