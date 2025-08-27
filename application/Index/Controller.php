<?php

namespace Commercial\Application\Index;

use Commercial\Framework\Http\Session;

/** @Controller("/") */
class Controller extends \Commercial\Framework\Web\Controller
{
    /** @Any */
    public function index()
    {
        if (Session::get("signed")) {
            return $this->render("Dashboard");
        } else {
            $this->redirect("/auth");
        }
    }
}
