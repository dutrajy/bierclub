<?php

namespace Commercial\Application\Printer;

class Printer
{
    protected const device = "/dev/ttyUSB0";

    public static function read()
    {
        exec("stty -F static::device 115200 raw");
        $fp = fopen(static::device,'r');

        $msg = fread($fp, 78);

        fclose($fp);

        return $msg;
    }

    public static function print($msg)
    {
	setlocale(LC_CTYPE, 'en_US.utf8');
        $msg = iconv('UTF-8', 'ASCII//TRANSLIT', $msg);

        exec("stty -F static::device 115200 raw");
        $fp = fopen(static::device,'w+');
        fwrite($fp, $msg . "\n\n");
        fclose($fp);
    }

    public static function keepOnline()
    {
        exec("stty -F static::device 115200 raw");
        $fp = fopen(static::device,'w+');
        fwrite($fp, "\x1D\xFF");
        fclose($fp);
    }
}
