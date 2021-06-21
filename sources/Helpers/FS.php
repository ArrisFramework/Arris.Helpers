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
        if (!is_dir( $directory )) {
            return false;
        }
        
        $files = array_diff( scandir( $directory ), [ '.', '..' ] );
        
        foreach ($files as $file) {
            $target = "{$directory}/{$file}";
            (is_dir( $target ))
                ? self::rmdir( $target )
                : unlink( $target );
        }
        return rmdir( $directory );
    }
    
    /**
     * Максимальный размер закачиваемого файла
     *
     * @return mixed
     */
    public static function getMaxUploadSize()
    {
        $max_upload = (int)(ini_get( 'upload_max_filesize' ));
        $max_post = (int)(ini_get( 'post_max_size' ));
        $memory_limit = (int)(ini_get( 'memory_limit' ));
        return min( $max_upload, $max_post, $memory_limit );
    }
    
    /**
     * Finds path, relative to the given root folder, of all files and directories in the given directory and its sub-directories non recursively.
     * Will return an array of the form
     * array(
     *   'files' => [],
     *   'dirs'  => [],
     * )
     *
     * @param string $root
     * @return array[]
     */
    public static function read_all_files($root = '.')
    {
        $directory_content = [ 'files' => [], 'dirs' => [] ];
        $directories = array();
        $last_letter = $root[ strlen( $root ) - 1 ];
        $root
            = ($last_letter === '\\' || $last_letter === '/')
            ? $root
            : $root.DIRECTORY_SEPARATOR;
        
        $directories[] = $root;
        
        while (count( $directories )) {
            $dir = array_pop( $directories );
            if ($handle = opendir( $dir )) {
                while (false !== ($file = readdir( $handle ))) {
                    if ($file === '.' || $file === '..') {
                        continue;
                    }
                    $file = $dir.$file;
                    if (is_dir( $file )) {
                        $directory_path = $file . DIRECTORY_SEPARATOR;
                        $directories[] = $directory_path;
                        $directory_content[ 'dirs' ][] = $directory_path;
                    } elseif (is_file( $file )) {
                        $directory_content[ 'files' ][] = $file;
                    }
                }
                closedir( $handle );
            }
        }
        
        return $directory_content;
    }
    
}