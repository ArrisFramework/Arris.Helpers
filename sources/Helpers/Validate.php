<?php

namespace Arris\Helpers;

class Validate
{
    /**
     * @param $value
     * @param $allowed_values_array
     * @param null $invalid_value
     * @return mixed|null
     */
    public static function checkAllowedValue( $value, $allowed_values_array , $invalid_value = null )
    {
        if (empty($value)) {
            return $invalid_value;
        }
        
        $key = array_search( $value, $allowed_values_array);
        
        return  ($key !== false)
                ? $allowed_values_array[ $key ]
                : $invalid_value;
    }
    
}