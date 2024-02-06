<?php

namespace Arris\Helpers;

class Options implements OptionsInterface
{
    /**
     * Перезаписывает набор дефолтных значений на основе переданного списка опций
     *
     * @param $defaults
     * @param $options
     * @return mixed
     */
    public static function overrideDefaults($defaults, $options)
    {
        $source = $defaults;
        \array_walk($source, static function (&$default, $key) use ($options) {
            if (\array_key_exists($key, $options)) {
                $default = $options[$key];
            }
        } );
        return $source;
    }
    
    
    
}