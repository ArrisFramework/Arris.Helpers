<?php

namespace Arris\Helpers;

use Throwable;

class Objects
{

    /**
     * Рекурсивно проверяет существование проперти у объекта
     *
     * https://gist.github.com/nyamsprod/10adbef7926dbc449e01eaa58ead5feb
     *
     * @param $object
     * @param string $path
     * @param string $separator
     * @return bool
     */
    public static function property_exists_recursive($object, string $path, string $separator = '->'): bool
    {
        if (!\is_object($object)) {
            return false;
        }

        $properties = \explode($separator, $path);
        $property = \array_shift($properties);
        if (!\property_exists($object, $property)) {
            return false;
        }

        try {
            $object = $object->$property;
        } catch (Throwable $e) {
            return false;
        }

        if (empty($properties)) {
            return true;
        }

        return Objects::property_exists_recursive($object, \implode('->', $properties));
    }

    /**
     * Получает значение проперти у объекта по nested-ключу
     *
     * @param $object
     * @param string $path
     * @param string $separator
     * @param $default
     * @return mixed|null
     */
    public static function property_get_recursive($object, string $path, string $separator = '->', $default = null)
    {
        $properties = \explode($separator, $path);

        foreach ($properties as $p) {
            if (!\property_exists($object, $p)) {
                return $default;
            } else {
                $object = $object->{$p};
            }
        }
        return $object;
    }

}