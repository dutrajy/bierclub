<?php

namespace Commercial\Framework\Web;

class Controller
{
    protected function render($path, $data = [])
    {
        return View::render($path, $data);
    }

    protected function redirect($path)
    {
        header("Location: {$path}");
        die();
    }
}
