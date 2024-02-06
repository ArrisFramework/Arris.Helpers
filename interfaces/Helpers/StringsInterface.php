<?php

namespace Arris\Helpers;

interface StringsInterface
{
    /**
     * Plural form of number
     *
     * @param $number
     * @param mixed $forms (array or string with glues, x|y|z or [x,y,z]
     * @param string $glue
     * @return string
     */
    public static function pluralForm($number, $forms, string $glue = '|'):string;

    /**
     * Convert string with last letter modifier to integer form
     * K = 2^10
     * M = 2^20
     * G = 2^30
     *
     * @param $val
     * @return int|string
     */
    public static function return_bytes($val):int;

}