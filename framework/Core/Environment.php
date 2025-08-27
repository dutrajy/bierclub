<?php

namespace Commercial\Framework\Core;

class Environment
{
    public static function utf8()
    {
        mb_internal_encoding('UTF-8');
        mb_http_input('UTF-8');
        mb_http_output('UTF-8');
        ini_set('default_charset', 'UTF-8');
    }

    public static function showErrors()
    {
        ini_set("xdebug.scream", "On");
        ini_set("display_startup_errors", "On");
        ini_set("display_errors", "On");
        ini_set("html_errors", "On");
        ini_set("track_errors", "On");
        error_reporting(E_ALL);
    }

    public static function loadEnv($fileName)
    {
        $newEnv = \parse_ini_file($fileName, false, \INI_SCANNER_TYPED);
        $_SERVER["ENV"] = $_ENV = \array_merge($_ENV, $_SERVER["ENV"] ?? [], $newEnv);
        foreach ($newEnv as $key => $value) {
            putenv("{$key}={$value}");
        }
    }

}
