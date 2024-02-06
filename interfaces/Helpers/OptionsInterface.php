<?php

namespace Arris\Helpers;

interface OptionsInterface
{
    /**
     * Перезаписывает набор дефолтных значений на основе переданного списка опций
     *
     * @param $defaults
     * @param $options
     * @return mixed
     */
    public static function overrideDefaults($defaults, $options);
    
    
    
}