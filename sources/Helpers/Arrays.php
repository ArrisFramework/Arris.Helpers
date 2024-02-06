<?php
/**
 * User: Karel Wintersky
 * Library: https://github.com/KarelWintersky/Arris.Helpers
 */

namespace Arris\Helpers;

use JsonException;

class Arrays
{
    /**
     * Разбивает строку по $separator и возвращает массив значений, приведенных к типу.
     *
     * Список возможных типов: 'int', 'integer', 'bool', 'boolean', 'float', 'double', 'str', 'string', 'array', 'object'
     */
    public static function explodeToType(string $string, string $separator = ' ', $callback = '')
    {
        return
            \array_map( static function ($i) use ($separator, $callback)
            {
                if (empty($callback)) {
                    return $i;
                }

                if (\is_string($callback)) {
                    $allowed_types = ['int', 'integer', 'bool', 'boolean', 'float', 'double', 'str', 'string', 'array', 'object'];
                    $type = $callback;
                    if ($type === 'str') {
                        $type = 'string';
                    }

                    if (\in_array($callback, $allowed_types)) {
                        \settype($i, $type);
                    }

                    return $i; // anyway return $i
                }

                if (Dataset::is_closure($callback)) {
                    return \call_user_func($callback, $i);
                }

                return $i;

            }, \explode($separator, $string)
            );
    }

    /**
     * Разбивает строку по $separator и возвращает массив значений, приведенных к INTEGER
     *
     * @param string $string
     * @param string $separator
     * @return array
     */
    public static function explodeToInt(string $string, string $separator = ' '):array
    {
        return \array_map(static function ($i) use ($separator) { return (int)$i; }, \explode($separator, $string));
    }

    /**
     *
     * @param $keys
     * @param array $array
     * @return bool
     */
    public static function arrayContainKeys($keys, array $array):bool
    {
        if (!\is_array($keys)) {
            $keys = [$keys];
        }

        if (empty($array)) {
            return false;
        }

        if (empty($keys)) {
            return true;
        }

        $is_correct = true;
        foreach ($keys as $key) {
            $is_correct = $is_correct && \array_key_exists($key, $array);
        }
        return $is_correct;
    }
    
    /**
     * Хелпер преобразования всех элементов массива к типу integer
     *
     * @param array $input
     * @return array
     */
    public static function map_to_integer($input): array
    {
        if (!\is_array($input) || empty($input)) {
            return [];
        }

        return \array_map( static function ($i) {
            return (int)$i;
        }, $input);
    }

    /**
     * Map
     *
     * @param $input
     * @param callable $callback
     * @return array
     */
    public static function map($input, callable $callback):array
    {
        if (!\is_array($input) || empty($input)) {
            return [];
        }
        
        if (!\is_callable($callback)) {
            $callback = static function() {
            };
        }
    
        return \array_map($callback, $input);
    }
    
    /**
     *
     * Аналог list($dataset['a'], $dataset['b']) = explode(',', 'AAAAAA,BBBBBB');
     * только с учетом размерности массивов и с дефолтными значениями
     *
     * Example: array_fill_like_list($dataset, ['a', 'b', 'c'], explode(',', 'AAAAAA,BBBBBB'), 'ZZZZZ' );
     *
     * @package KarelWintersky/CoreFunctions
     *
     * @param array $target_array
     * @param array $indexes
     * @param array $source_array
     * @param null $default_value
     */
    function fill_like_list(array &$target_array, array $indexes, array $source_array, $default_value = NULL)
    {
        foreach ($indexes as $i => $index) {
            $target_array[ $index ] = \array_key_exists($i, $source_array) ? $source_array[ $i ] : $default_value;
        }
    }
    
    /**
     * Sort array in given order by key
     * Returns array
     *
     * Alias of array_sort_in_given_order()
     *
     * @param $array - array for sort [ [ id, data...], [ id, data...], ...  ]
     * @param $order - order (as array of sortkey values) [ id1, id2, id3...]
     * @param $sort_key - sorting key (id)
     * @return mixed
     */
    public static function sort_with_order(array $array, array $order, $sort_key):array
    {
        \usort($array, function ($home, $away) use ($order, $sort_key) {
            $pos_home = \array_search($home[$sort_key], $order);
            $pos_away = \array_search($away[$sort_key], $order);
            return $pos_home - $pos_away;
        });
        return $array;
    }
    
    public static function filter(array $input, $callback = null, $flag = 0)
    {
        return \array_filter($input, $callback, $flag);
    }
    
    /**
     *
     *
     * @param array $input
     * @param callable $callback
     * @return array|mixed|null
     */
    public static function searchCallback(array $input, callable $callback)
    {
        if (empty($input)) {
            return [];
        }
    
        if (!\is_callable($callback)) {
            $callback = static function() { };
        }
    
        foreach ($input as $item) {
            $v = \call_user_func($callback, $item);
            if ( $v === true ) {
                return $item;
            }
        }
        return null;
        
    }
    
    /**
     * Возвращает новый датасет, индекс для строк которого равен значению колонки строки
     * Предназначен для переформатирования PDO-ответов, полученных в режиме FETCH_ASSOC
     *
     * [ 0 => [ 'id' => 5, 'data' => 10], 1 => [ 'id' => 6, 'data' => 12] .. ]
     * При вызове с аргументами ($dataset, 'id') возвращает
     * [ 5 => [ 'id' => 5, 'data' => 10], 6 => [ 'id' => 6, 'data' => 12] .. ]
     *
     * @param $dataset
     * @param $column_id
     * @return array
     */
    public static function groupDatasetByColumn($dataset, $column_id)
    {
        $result = [];
        \array_map(static function ($row) use (&$result, $column_id){
            $result[ $row[ $column_id ] ] = $row;
        }, $dataset);
        return $result;
    }

    public static function arrayDepth($array, $level = 0)
    {
        if (!$array) {
            return 0;
        }

        $current = \current($array);
        $level++;

        if ( !\is_array($current) ) {
            return $level;
        }

        return self::arrayDepth($current, $level);
    }

    /**
     * array_search_callback() аналогичен array_search() , только помогает искать по неодномерному массиву.
     *
     * @param array $a
     * @param callable $callback
     * @return mixed|null
     */
    public static function array_search_callback(array $a, callable $callback)
    {
        foreach ($a as $item) {
            $v = \call_user_func($callback, $item);
            if ( $v === true ) {
                return $item;
            }
        }
        return null;
    }

    /**
     *
     * @param $data
     * @return false|string
     * @throws JsonException
     */
    public static function jsonize($data)
    {
        return \json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
    }
    
    /**
     * Flatten multi-dimensional array
     *
     * @param $arr
     * @return array
     */
    public static function flatten($arr)
    {
        return \array_merge(...\array_values($arr));
    }

    /**
     *
     *
     * @param array $data
     * @param ...$args
     * @return array
     */
    public static function order_by(array $data, ...$args): array
    {
        foreach ($args as $n => $field) {
            if (\is_string($field)) {
                $tmp = [];
                foreach ($data as $key => $row) {
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;
                unset($tmp);
            }
        }

        $args[] = &$data;
        \call_user_func_array('array_multisort', $args);
        return \array_pop($args);
    }
    
    
    
}

# -eof-
