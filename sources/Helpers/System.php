<?php

namespace Arris\Helpers;

use RuntimeException;

class System
{
    /**
     * @param $actor
     * @return array
     */
    public static function compileCallbackHandler($actor)
    {
        if ($actor instanceof \Closure) {
            return [
                $actor, []
            ];
        }

        //@todo: что делать, если инстанс класса уже создан и проинициализирован и лежит в APP::REPO
        // $app->set(Banners::class, new Banners(....) );
        // То есть $handler может быть не строкой, а массивом [ $app->get(Banners::class), 'loadBanners' ]

        // 0 - имя класса + метода
        // 1 - массив параметров
        if (is_array($actor)) {
            $handler = $actor[0];
            $params = (count($actor) > 1) ? $actor[1] : [];

            //@todo: нужна проверка is_string()
            if (strpos($handler, '@') > 0) {
                // dynamic class
                [$class, $method] = explode('@', $handler, 2);

                if (!class_exists($class)) {
                    throw new RuntimeException("Class {$class} not defined.", 500);
                }

                if (!method_exists($class, $method)) {
                    throw new RuntimeException("Method {$method} not declared at {$class} class", 500);
                }

                $actor = [ new $class, $method ];
            } elseif (strpos($handler, '::') > 0) {
                [$class, $method] = explode('::', $handler, 2);

                if (!class_exists($class)) {
                    throw new RuntimeException("Class {$class} not defined.", 500);
                }

                if (!method_exists($class, $method)) {
                    throw new RuntimeException("Static method {$method} not declared at {$class} class", 500);
                }

                $actor = [ $class, $method ];
            } else {
                // function
                if (!function_exists($handler)){
                    throw new RuntimeException("Handler function {$handler} not found", 500);
                }

                $actor = $handler;
            }
        } // is_array
        return [
            $actor, $params
        ];

    } // detectCallbackHandler



}