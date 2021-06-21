<?php


namespace Arris\Helpers;


class Debug
{
    
    /**
     * Печать массива в том виде, в каком его понимает php
     *
     * @param $array
     * @param int $level
     * @return string
     */
    public static function array_code($array, $level = 1)
    {
        $php = $tabs = $breaks = '';
        if (is_array($arr)) {
            for ($n = 0; $n < $level; $n++) {
                $tabs .= "\t";
                if ($n > 0) {
                    $breaks .= "\t";
                }
            }
            $vals = [];
            foreach ($arr as $key => $val) {
                $vals[] = is_array($val) ?
                    "'" . $key . "'=>" . self::array_code($val, $level + 1) :
                    "'" . $key . "'=>'" . $val . "'";
            }
            $php = "array(\r" . $tabs . implode(",\r" . $tabs, $vals) . "\r" . $breaks . ")";
        }
    
        return $php;
    }
    
    /**
     * Возможность вызвать любой метод (даже приватный)
     *
     * @param $object
     * @param $method
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     */
    public static function callMethod($object, $method, $args = [])
    {
        $classReflection = new \ReflectionClass(get_class($object));
        $methodReflection = $classReflection->getMethod($method);
        $methodReflection->setAccessible(true);
        $result = $methodReflection->invokeArgs($object, $args);
        $methodReflection->setAccessible(false);
    
        return $result;
    }
    
}