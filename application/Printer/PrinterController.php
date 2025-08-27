<?php

namespace Commercial\Application\Printer;

use Commercial\Application\Index\Controller;

/** @Controller("/printer") */
class PrinterController extends Controller
{
    protected $device = "/dev/ttyUSB0";

    /** @Get */
    public function index()
    {
        return $this->render("Printer/Form");
    }

    /** @Get("/read") */
    public function read()
    {
        exec("stty -F $this->device 115200 raw");
        $fp = fopen($this->device,'r');

        $msg = fread($fp, 78);

        fclose($fp);

        return $msg;
    }

    /** @Post("/print") */
    public function print($request)
    {    
	setlocale(LC_CTYPE, 'en_US.utf8');
 	$msg = iconv('UTF-8', 'ASCII//TRANSLIT', $request->getParsedBody()["msg"]);
        exec("stty -F $this->device 115200 raw");
        $fp = fopen($this->device,'w+');
        fwrite($fp, $msg . "\n\n\n");
        fclose($fp);
        $this->redirect("/printer");
    }

    /** @Get("/keep") */
    public function keepOnline($request)
    {
        exec("stty -F $this->device 115200 raw");
        $fp = fopen($this->device,'w+');
        fwrite($fp, "\x1D\x7C\x01\xFF");
        fclose($fp);
        $this->redirect("/printer");
    }

    /** @Get("/image") */
    public function image($request)
    {
        exec("stty -F $this->device 115200 raw");
        $fp = fopen($this->device,'w+');
        fwrite($fp, "\x1d\x6b\x02\x0d\x36\x39\x30\x31\x32\x33\x34\x35\x36\x37\x38\x39\x32");
        fclose($fp);
        $this->redirect("/printer");
    }
}
