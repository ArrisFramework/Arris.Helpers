<?php

namespace Arris\Helpers;

class Url
{
    /**
     * alais of http_redirect() method
     *
     * @param $uri
     * @param int $code
     * @param string $scheme
     * @param bool $replace_headers
     */
    public static function redirect($uri, $code = 302, $scheme = '', $replace_headers = true)
    {
        if ((strpos( $uri, "http://" ) !== false || strpos( $uri, "https://" ) !== false)) {
            header("Location: {$uri}", $replace_headers, $code);
            exit(0);
        }
        
        $scheme = $scheme ?: 'http';
        $scheme = str_replace('://', '', $scheme);
        
        header("Location: {$scheme}://{$_SERVER['HTTP_HOST']}{$uri}", $replace_headers, $code);
        exit(0);
    }
    
    /**
     * Проверяет, является ли переданная строка корректным URL (http/https/ftp), включая IDN
     *
     * @param $url
     * @return false|int
     */
    public static function filter_validate_url($url)
    {
        return preg_match('#((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i', $url);
    }
    
}