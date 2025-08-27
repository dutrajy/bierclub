<?php

namespace Commercial\Application\Bierclub;

use Commercial\Framework\Database\Connection;
use Commercial\Application\Financial\Account;
use Commercial\Application\Financial\AccountOperation;
use Exception;

/** @Controller("/bierclub") */
class Controller extends \Commercial\Application\Common\Controller
{
    /** @Get("/members") */
    public function index()
    {
        return $this->render(
            "Bierclub/Members/List",
            [
                "members" => Member::findAll(),
            ]
        );
    }

    /** @Get("/members/new") */
    public function new()
    {
        return $this->render(
            "Bierclub/Members/Form",
            [
                "member" => new Member(),
                "action" => "create",
                "user" => $this->user,
                "members" => Member::findAll()
            ]
        );
    }

    /** @Post("/members/create") */
    public function create($request)
    {
        Connection::beginTransaction($request);

        $member = new Member($request->getParsedBody()["member"]);
        $member->setCard("%" . $member->getCard() . "?");

        if ($_FILES["member_image"]) {
            $targetDirectory = \realpath(__DIR__ . "/../../public/images/uploads/");
            $ext = pathinfo($_FILES["member_image"]["name"], PATHINFO_EXTENSION);
            $image = md5(uniqid());
            $targetFile = $targetDirectory . "/" . $image . "." . $ext;

            move_uploaded_file($_FILES["member_image"]["tmp_name"], $targetFile);

            $member->setImage($image . "." . $ext);
        }

        $member->setCreator($this->user);
        $member->setUpdater($this->user);

        $member->insert();

        if (!$member->getTitular() || $member->getTitular() === $member->getId()) {
            $account = new Account();
            $account->setDescription("403 Bier Club Member Account");
            $account->setCreator($this->user);
            $account->setUpdater($this->user);
            $account->setOwner($member);

            $account->insert();
        }

        Connection::commit();

        $this->redirect("/bierclub/members");
    }

    /** @Get("/members/edit/{id}") */
    public function edit($request, $id)
    {
        return $this->render(
            "Bierclub/Members/Form",
            [
                "member" => Member::findOne(["id" => $id]),
                "action" => "update",
                "user" => $this->user,
                "members" => Member::findAll()
            ]
        );
    }

    /** @Post("/members/update") */
    public function update($request)
    {
        Connection::beginTransaction($request);

        $member = Member::findOne(["id" => $request->getParsedBody()["member"]["id"]]);
        $member->fromAssociativeArray($request->getParsedBody()["member"]);

        if ($_FILES["member_image"]) {
            $targetDirectory = \realpath(__DIR__ . "/../../public/images/uploads/");
            $ext = pathinfo($_FILES["member_image"]["name"], PATHINFO_EXTENSION);
            $image = md5(uniqid());
            $targetFile = $targetDirectory . "/" . $image . "." . $ext;

            move_uploaded_file($_FILES["member_image"]["tmp_name"], $targetFile);

            $member->setImage($image . "." . $ext);
        }

        $member->setCreator($this->user);
        $member->setUpdater($this->user);

        $member->update();

        Connection::commit();

        $this->redirect("/bierclub/members");
    }

    /** @Post("/member/{id}/credit") */
    public function credit($request, $id)
    {
        $amount = $request->getParsedBody()["amount"];
        $amount = str_replace(".", "", $amount);
        $amount = str_replace(",", ".", $amount);

        if ($this->user->getRole() === "manager" || $this->user->getRole() === "administrator") {
            try {
                Connection::beginTransaction();
                $member = Member::findOne(["id" => $id]);
                $account = $member->getAccount();

                $operation = new AccountOperation();
                $operation->setDescription("Credit inserted by {$this->user->getRole()} - {$this->user->getFullName()} #{$this->user->getId()}");
                $operation->setCreator($this->user);
                $operation->setAmount($amount);
                $operation->setAccount($account);

                $operation->debitFrom(Account::findOne(["owner_type" => "users", "owner_id" => $this->user->getId()]));

                Connection::commit();

                $this->redirect("/financial/accounts/details/" . $account->getId());
            } catch(Exception $e) {
                var_dump($e);
                return "Failed";
            }

        } else {
            return "Operation Not Allowed";
        }
    }
}
