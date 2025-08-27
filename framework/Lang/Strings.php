<?php

namespace Commercial\Framework\Lang;

class Strings
{
    public static function startsWith($string, $start)
    {
        return \strpos($string, $start) === 0;
    }

    public static function normal($string)
    {
        $normalized = \preg_replace("#([[:lower:]])([[:upper:]])#u", "\\1 \\2", $string);
        $normalized = \preg_replace("#([[:punct:]]+)|(\\s+)#u", " ", $normalized);
        $normalized = strtolower($normalized);
        return $normalized;
    }

    public static function clean($string)
    {
        $cleaned = \preg_replace("#([[:punct:]]+)|(\\s+)#u", "", $string);
        return $cleaned;
    }

    public static function title($string)
    {
        $titlelized = static::normal($string);
        $titlelized = \ucwords($titlelized);
        return $titlelized;
    }

    public static function pascal($string)
    {
        $pascalized = static::normal($string);
        $pascalized = ucwords($pascalized);
        $pascalized = str_replace(" ", "", $pascalized);
        return $pascalized;
    }

    public static function camel($string)
    {
        $camelized = static::normal($string);
        $camelized = ucwords($camelized);
        $camelized = str_replace(" ", "", $camelized);
        $camelized = lcfirst($camelized);
        return $camelized;
    }

    public static function snake($string)
    {
        $snaked = static::normal($string);
        $snaked = str_replace(" ", "_", $snaked);
        return $snaked;
    }

    public static function plural($string)
    {
        return $string . "s";
    }

    public static function removePrefix($string, $prefix)
    {
        if (self::startsWith($string, $prefix)) {
            return \substr($string, strlen($prefix));
        } else {
            return $string;
        }
    }

    public static function removeSuffix($string, $suffix)
    {
        return \substr($string, 0, strpos($string, $suffix));
    }
}
