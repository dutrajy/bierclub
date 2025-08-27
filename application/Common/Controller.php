<?php

namespace Commercial\Application\Common;

use Commercial\Framework\Http\Session;
use Commercial\Application\Users\User;

class Controller extends \Commercial\Framework\Web\Controller
{
    public function __construct()
    {
        Session::start();

        if (!Session::get("signed")) {
            $this->redirect("/auth");
        } else {
            $this->user = User::findOne(["id" => Session::get("user_id")]);
        }
    }
}
