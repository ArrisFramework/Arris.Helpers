<?php

namespace Arris\Helpers;

class Server
{
    public const HTTP_CODES = [
        100 => "HTTP/1.1 100 Continue",
        101 => "HTTP/1.1 101 Switching Protocols",
        200 => "HTTP/1.1 200 OK",
        201 => "HTTP/1.1 201 Created",
        202 => "HTTP/1.1 202 Accepted",
        203 => "HTTP/1.1 203 Non-Authoritative Information",
        204 => "HTTP/1.1 204 No Content",
        205 => "HTTP/1.1 205 Reset Content",
        206 => "HTTP/1.1 206 Partial Content",
        300 => "HTTP/1.1 300 Multiple Choices",
        301 => "HTTP/1.1 301 Moved Permanently",
        302 => "HTTP/1.1 302 Found",
        303 => "HTTP/1.1 303 See Other",
        304 => "HTTP/1.1 304 Not Modified",
        305 => "HTTP/1.1 305 Use Proxy",
        307 => "HTTP/1.1 307 Temporary Redirect",
        400 => "HTTP/1.1 400 Bad Request",
        401 => "HTTP/1.1 401 Unauthorized",
        402 => "HTTP/1.1 402 Payment Required",
        403 => "HTTP/1.1 403 Forbidden",
        404 => "HTTP/1.1 404 Not Found",
        405 => "HTTP/1.1 405 Method Not Allowed",
        406 => "HTTP/1.1 406 Not Acceptable",
        407 => "HTTP/1.1 407 Proxy Authentication Required",
        408 => "HTTP/1.1 408 Request Time-out",
        409 => "HTTP/1.1 409 Conflict",
        410 => "HTTP/1.1 410 Gone",
        411 => "HTTP/1.1 411 Length Required",
        412 => "HTTP/1.1 412 Precondition Failed",
        413 => "HTTP/1.1 413 Request Entity Too Large",
        414 => "HTTP/1.1 414 Request-URI Too Large",
        415 => "HTTP/1.1 415 Unsupported Media Type",
        416 => "HTTP/1.1 416 Requested range not satisfiable",
        417 => "HTTP/1.1 417 Expectation Failed",
        500 => "HTTP/1.1 500 Internal Server Error",
        501 => "HTTP/1.1 501 Not Implemented",
        502 => "HTTP/1.1 502 Bad Gateway",
        503 => "HTTP/1.1 503 Service Unavailable",
        504 => "HTTP/1.1 504 Gateway Time-out"
    ];
    
    /**
     *
     * @return bool
     */
    public static function is_ssl():bool
    {
        if (isset($_SERVER['HTTPS'])) {
            if ('on' == \strtolower($_SERVER['HTTPS'])) {
                return true;
            }
            if ('1' == $_SERVER['HTTPS']) {
                return true;
            }
        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }
    
    /**
     * alias of http_redirect() method
     *
     * @param $uri
     * @param int $code
     * @param string $scheme
     * @param bool $replace_headers
     */
    public static function redirect($uri, $code = 302, $replace_headers = true)
    {
        if ((\strpos( $uri, "http://" ) !== false || \strpos( $uri, "https://" ) !== false)) {
            \header("Location: {$uri}", $replace_headers, $code);
            exit(0);
        }
        
        $scheme = (self::is_ssl() ? "https://" : "http://");
        $scheme = \str_replace('://', '', $scheme);
        
        \header("Location: {$scheme}://{$_SERVER['HTTP_HOST']}{$uri}", $replace_headers, $code);
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
        return \preg_match('#((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i', $url);
    }
    
    /**
     * @return mixed|string|null
     */
    public static function getIP()
    {
        if (PHP_SAPI === 'cli') {
            return '127.0.0.1';
        }
        
        if (!isset ($_SERVER['REMOTE_ADDR'])) {
            return NULL;
        }
        
        if (\array_key_exists("HTTP_X_FORWARDED_FOR", $_SERVER)) {
            $http_x_forwarded_for = \explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
            $client_ip = \trim(\end($http_x_forwarded_for));
            if (\filter_var($client_ip, FILTER_VALIDATE_IP)) {
                return $client_ip;
            }
        }
        
        return \filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) ? $_SERVER['REMOTE_ADDR'] : NULL;
    }
    
    /**
     *
     * @param $file_post
     * @return array
     */
    public static function reArrangeFilesPOST($file_post)
    {
        $rearranged_files = [];
        $file_count = \count($file_post['name']);
        $file_keys = \array_keys($file_post);
        
        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $rearranged_files[$i][$key] = $file_post[$key][$i];
            }
        }
        
        return $rearranged_files;
    }
    
    
    
}