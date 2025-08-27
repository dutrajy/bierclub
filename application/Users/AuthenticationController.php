<?php

namespace Commercial\Application\Users;

use Commercial\Framework\Http\Session;
use Commercial\Application\Users\User;

/** @Controller("/auth") */
class AuthenticationController extends \Commercial\Framework\Web\Controller
{
    /** @Any */
    public function index()
    {
        return $this->render("Auth/Signin");
    }

    /** @Post("/signin") */
    public function signin($request)
    {
        $user = User::findOne([
            "username" => $request->getParsedBody()["username"],
            "password" => sha1($request->getParsedBody()["password"]),
        ]);

        if ($user) {
            Session::set("signed", true);
            Session::set("user_id", $user->getId());
            $this->redirect("/");

        } else {
            Session::destroy();
            $this->redirect("/auth");
        }
    }

    /** @Get("/signout") */
    public function signout()
    {
        Session::destroy();
        $this->redirect("/auth");
    }
}
