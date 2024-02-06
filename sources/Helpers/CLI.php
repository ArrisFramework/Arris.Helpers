<?php

namespace Arris\Helpers;

class CLI
{
    /**
     * Парсит опции командной строки и заполняет массив значениями true/false
     *
     * $options = parseCLIOptions('h::f', ['make', 'help']);
     *
     * Создаст массив [ 'h' => false, 'f' => false, 'make' => false, 'help' => false ] , а потом поставит true для тех опций,
     * которые заданы в командной строке
     *
     * НЕ РАБОТАЕТ КОРРЕКТНО экспорт значений: $options_present[$k] = $export_values ? $parsed_options[$k] : array_key_exists($k, $parsed_options);
     * Если задан ключ $export_values = false - то вместо экспорта значения опции (string) ставится только флаг её наличия
     *
     * @param string $short_options
     * @param array $long_options
     * @param bool $export_values
     * @return array
     */
    public static function parseOptions(string $short_options, array $long_options = [], $export_values = true)
    {
        $options_present = [];
        // превращаем длинные опции в список $set->$v = false
        foreach ($long_options as $v) {
            $options_present [ $v ] = false;
        }
        
        // превращаем короткие опции в список $set->$v = false
        $splitted_short_options = preg_split('/:?/', $short_options, -1, PREG_SPLIT_NO_EMPTY);
        if (\is_array($splitted_short_options)) {
            foreach ($splitted_short_options as $v) {
                $options_present [ $v ] = false;
            }
        }
        
        // парсим опции, устанавливая true там где опция реально присутствует
        $parsed_options = getopt($short_options, $long_options);
        if (\is_array($parsed_options)) {
            foreach ($parsed_options as $k => $v) {
                $options_present[$k] = \array_key_exists($k, $parsed_options);
            }
        }
        
        return $options_present;
    }
    
}