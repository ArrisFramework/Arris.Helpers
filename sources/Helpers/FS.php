<?php


namespace Arris\Helpers;


class FS
{
    /**
     * Рекурсивный rmdir
     *
     * @param $directory
     * @return bool
     */
    public static function rmdir($directory): bool
    {
        if (!is_dir($directory)) {
            return false;
        }
        
        $files = array_diff(scandir($directory), ['.', '..']);
        
        foreach ($files as $file) {
            $target = "{$directory}/{$file}";
            (is_dir($target))
                ? self::rmdir($target)
                : unlink($target);
        }
        return rmdir($directory);
    }

}