<?php

namespace Commercial\Application\Financial;

use Commercial\Application\Users\User;

/** @Controller("/financial") */
class Controller extends \Commercial\Application\Common\Controller
{
    /** @Any */
    public function index() {
        return $this->render(
            "Financial/AccountsList",
            [
                "accounts" => Account::findAll(),
            ]
        );
    }

    /** @Any("/accounts/new") */
    public function newAccount() {
        return $this->render(
            "Financial/AccountNew",
            [
                "users" => User::findAll(),
                "account" => new Account(),
                "action" => "create"
            ]
        );
    }

    /** @Post("/accounts/create") */
    public function createAccount($request)
    {
        $account = new Account($request->getParsedBody()["account"]);
        $account->setOwner(User::findOne(["id" => $request->getParsedBody()["owner"]["id"]]));
        $account->setCreator($this->user);
        $account->setUpdater($this->user);
        $account->insert();

        $this->redirect("/financial");
    }

    /** @Any("/accounts/details/{id}") */
    public function detailsAccount($request, $id)
    {
        return $this->render(
            "Financial/AccountForm",
            [
                "account" => Account::findOne(["id" => $id]),
                "action" => "update",
            ]
        );
    }
}
