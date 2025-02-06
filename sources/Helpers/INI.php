<?php

namespace Arris\Helpers;

/**
 * Класс, помогающий работать с файлами конфигурации .INI, в том числе с php.ini
 */
class INI
{
    public static function get_ini_value($key)
    {
        return Strings::return_bytes(ini_get($key));
    }

    /**
     * Максимальный размер закачиваемого файла
     *
     * @return int
     */
    public static function getMaxUploadFilesize():int
    {
        return min(
            INI::get_ini_value('post_max_size'),
            INI::get_ini_value('upload_max_filesize'),
            INI::get_ini_value('memory_limit')
        );
    }

}