<?php

namespace Commercial\Framework\Http;

class Session
{
    public static function start()
    {
        if(!session_id()) {
            session_start();
        }
    }

    public static function set($property, $value)
    {
        static::start();
        $_SESSION[$property] = $value;
    }

    public static function get($property)
    {
        static::start();
        if (isset($_SESSION[$property])) {
            return $_SESSION[$property];
        }
    }

    public static function destroy()
    {
        static::start();
        $_SESSION = [];
        session_unset();
        session_destroy();
    }
}
