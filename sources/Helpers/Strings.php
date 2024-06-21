<?php

namespace Arris\Helpers;

class Strings
{

    /**
     * Convert string with last letter modifier to integer form
     * K = 2^10
     * M = 2^20
     * G = 2^30
     *
     * @param $val
     * @return int|string
     */
    public static function return_bytes($val):int
    {
        $val = \trim($val);
        $last = \strtolower( $val[ \strlen($val)-1 ] );
        $val = (int)$val;

        switch($last) {
            case 'g':
                $val = $val << 10;
            case 'm':
                $val = $val << 10;
            case 'k':
                $val = $val << 10;
        }

        return $val;
    }

    /**
     * trims text to a space then adds ellipses if desired
     *
     * @param string $input text to trim
     * @param int $length in characters to trim to
     * @param bool $ellipses if ellipses (...) are to be added
     * @param bool $strip_html if html tags are to be stripped
     * @param string $ellipses_text text to be added as ellipses
     * @return string
     *
     * http://www.ebrueggeman.com/blog/abbreviate-text-without-cutting-words-in-half
     *
     * https://www.php.net/manual/ru/function.wordwrap.php - см комментарии
     */
    public static function trim($input, $length, $ellipses = true, $strip_html = true, $ellipses_text = '...'):string
    {
        //strip tags, if desired
        if ($strip_html) {
            $input = strip_tags($input);
        }

        //no need to trim, already shorter than trim length
        if (mb_strlen($input) <= $length) {
            return $input;
        }

        //find last space within length
        $last_space = mb_strrpos(mb_substr($input, 0, $length), ' ');
        $trimmed_text = mb_substr($input, 0, $last_space);

        //add ellipses (...)
        if ($ellipses) {
            $trimmed_text .= $ellipses_text;
        }

        return $trimmed_text;
    }

    /**
     * Multibyte string replace
     *
     * @param string|string[] $search  the string to be searched
     * @param string|string[] $replace the replacement string
     * @param string          $subject the source string
     * @param int             &$count  number of matches found
     *
     * @return string replaced string
     * @author Rodney Rehm, imported from Smarty
     *
     */
    public static function str_replace($search, $replace, $subject, &$count = 0)
    {
        if (!is_array($search) && is_array($replace)) {
            return false;
        }

        if (is_array($subject)) {
            // call mb_replace for each single string in $subject
            foreach ($subject as &$string) {
                $string = self::str_replace($search, $replace, $string, $c);
                $count += $c;
            }
            unset($string);

        } elseif (is_array($search)) {

            if (!is_array($replace)) {
                foreach ($search as &$string) {
                    $subject = self::str_replace($string, $replace, $subject, $c);
                    $count += $c;
                }
                unset($string);
            } else {
                $n = max(count($search), count($replace));
                while ($n--) {
                    $subject = self::str_replace(current($search), current($replace), $subject, $c);
                    $count += $c;
                    next($search);
                    next($replace);
                }
            }
        } else {
            $parts = mb_split(preg_quote($search), $subject);
            $count = count($parts) - 1;
            $subject = implode($replace, $parts);
        }
        return $subject;
    }

    /**
     * Like vsprintf, but accepts $args keys instead of order index.
     * Both numeric and strings matching /[a-zA-Z0-9_-]+/ are allowed.
     *
     * Example: vskprintf('y = %y$d, x = %x$1.1f', array('x' => 1, 'y' => 2))
     * Result:  'y = 2, x = 1.0'
     *
     * $args also can be object, then it's properties are retrieved
     * using get_object_vars().
     *
     * '%s' without argument name works fine too. Everything vsprintf() can do
     * is supported.
     *
     * @author Josef Kufner <jkufner(at)gmail.com>
     */
    public static function vksprintf($str, $args)
    {
        if (is_object($args)) {
            $args = get_object_vars($args);
        }
        $map = array_flip(array_keys($args));
        $new_str = preg_replace_callback('/(^|[^%])%([a-zA-Z0-9_-]+)\$/',
            function($m) use ($map) { return $m[1].'%'.($map[$m[2]] + 1).'$'; },
            $str);
        return vsprintf($new_str, $args);
    }

    /**
     * pluralForm - форма числительного (Plural form of number)
     *
     *
     * @param $number
     * @param mixed $forms (array or string with glues, x|y|z or [x,y,z]
     * @param string $glue
     * @return string
     */
    public static function pluralForm($number, $forms, string $glue = '|'):string
    {
        if (@empty($forms)) {
            return $number;
        }

        if (is_string($forms)) {
            $forms = explode($forms, $glue);
        } elseif (!is_array($forms)) {
            return $number;
        }

        switch (count($forms)) {
            case 1: {
                $forms[] = end($forms);
                $forms[] = end($forms);
                break;
            }
            case 2: {
                $forms[] = end($forms);
            }
        }

        return
            ($number % 10 == 1 && $number % 100 != 11)
                ? $forms[0]
                : (
            ($number % 10 >= 2 && $number % 10 <= 4 && ($number % 100 < 10 || $number % 100 >= 20))
                ? $forms[1]
                : $forms[2]
            );
    }


    public static function toFixedString($value, $separator = '.')
    {
        return str_replace(',', $separator, (string)$value);
    }

    /**
     * @param string $str
     * @param string $encoding
     * @return string Uc Words
     */
    public static function mb_ucwords($str, string $encoding = 'UTF-8')
    {
        $upper = true;

        $res = '';

        for ($i = 0; $i < mb_strlen($str, $encoding); $i++) {
            $c = mb_substr($str, $i, 1, $encoding);

            if ($upper) {
                $c = mb_convert_case($c, MB_CASE_UPPER, $encoding);
                $upper = false;
            }

            if ($c == ' ') {
                $upper = true;
            }

            $res .= $c;
        }
        return $res;
    }

    /**
     * @param string $str
     * @param string $encoding
     * @return string Uc first
     */
    public static function mb_ucfirst($str, $encoding = 'UTF-8')
    {
        $firstLetter = mb_substr($str, 0, 1, $encoding);
        $rest = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);

        return mb_strtoupper($firstLetter, $encoding) . $rest;
    }

    /**
     * @param string $str
     * @param string $encoding
     * @return string
     */
    public static function mb_strrev($str, $encoding = 'UTF-8')
    {
        $str = mb_convert_encoding($str, 'UTF-16BE', $encoding);

        return mb_convert_encoding(strrev($str), $encoding, 'UTF-16LE');
    }

    /**
     * @param string $input
     * @param int $pad_length
     * @param string $pad_string
     * @param int $pad_type
     * @param string $encoding
     * @return string
     */
    public static function mb_str_pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT, $encoding = 'UTF-8')
    {
        $diff = strlen($input) - mb_strlen($input, $encoding);

        return str_pad($input, $pad_length + $diff, $pad_string, $pad_type);
    }

    /**
     * @param string $string
     * @param int $mode only mode 1 and 3 is available
     * @param string $encoding
     * @return array|string
     * @throws \Exception
     */
    public static function mb_count_chars($string, $mode, $encoding = 'UTF-8')
    {
        $l = mb_strlen($string, $encoding);
        $unique = array();
        for ($i = 0; $i < $l; $i++) {
            $char = mb_substr($string, $i, 1, $encoding);
            if (!array_key_exists($char, $unique)) {
                $unique[$char] = 0;
            }
            $unique[$char]++;
        }

        if ($mode == 1) {
            return $unique;
        }

        if ($mode == 3) {
            $res = '';
            foreach ($unique as $index => $count) {
                $res .= $index;
            }
            return $res;
        }

        throw new \Exception('unsupported mode ' . $mode);
    }

    /**
     * @param string $string
     * @param int $split_length
     * @param string $encoding
     * @return array
     * @throws \Exception
     */
    public static function mb_str_split($string, $split_length = 1, $encoding = 'UTF-8')
    {
        if ($split_length <= 0) {
            throw new \Exception('The length of each segment must be greater than zero');
        }

        $ret = [];
        $len = mb_strlen($string, $encoding);
        for ($i = 0; $i < $len; $i += $split_length) {
            $ret[] = mb_substr($string, $i, $split_length, $encoding);
        }
        if (!$ret) {
            // behave like str_split() on empty input
            return [""];
        }

        return $ret;
    }

}

# -eof-