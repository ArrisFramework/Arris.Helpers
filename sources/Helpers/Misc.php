<?php


namespace Arris\Helpers;

/**
 * Class Misc
 *
 * Методы, не вошедшие (пока) в другие классы
 *
 * @package Arris\Helpers
 */
class Misc
{
    /**
     * Generate GUID
     *
     * @return string
     * @throws \Exception
     */
    public static function GUID()
    {
        if (\is_readable('/proc/sys/kernel/random/uuid')) {
            return \trim(\file_get_contents('/proc/sys/kernel/random/uuid'));
        }

        if (\function_exists('\com_create_guid') === true) {
            return \trim(com_create_guid(), '{}');
        }
    
        if (\function_exists('openssl_random_pseudo_bytes') === true) {
            $data = \openssl_random_pseudo_bytes(16);
            $data[6] = \chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
            $data[8] = \chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
            return \strtoupper( \vsprintf('%s%s-%s-%s-%s-%s%s%s', \str_split(bin2hex($data), 4)) );
        }
    
        return \sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            \random_int(0, 65535),
            \random_int(0, 65535),
            \random_int(0, 65535),
            \random_int(16384, 20479),
            \random_int(32768, 49151),
            \random_int(0, 65535),
            \random_int(0, 65535),
            \random_int(0, 65535)
        );
    }
    
    
   
    
}