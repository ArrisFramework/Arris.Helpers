<?php

namespace Arris\Helpers;

class Math
{
    /**
     *
     * @param $value
     * @param $min
     * @param $max
     * @return mixed
     */
    public static function toRange($value, $min, $max)
    {
        return \max($min, \min($value, $max));
    }
    
}