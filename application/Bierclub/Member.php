<?php

namespace Commercial\Application\Bierclub;

use Commercial\Application\Financial\Account;

class Member extends \Commercial\Application\Common\Base
{
    /** @Property */
    protected $fullName;

    /** @Property */
    protected $email;

    /** @Property */
    protected $phone;

    /** @Property */
    protected $image;

    /** @Property */
    protected $document;

    /** @Property */
    protected $card;

    /** @Property */
    protected $titular;

    /** @Property */
    protected $active;

    public function getAccount()
    {
        if ($this->getTitular()) {
            return Account::findOne(["owner_type" => $this->getTable(), "owner_id" => $this->getTitular()]);
        } else {
            return Account::findOne(["owner_type" => $this->getTable(), "owner_id" => $this->getId()]);
        }
    }

    public function getBalance()
    {
        return $this->getAccount()->getBalance();
    }

    public static function getTable()
    {
        return "bierclub_members";
    }

    public static function getNewCardCode($length = 76)
    {
        return \bin2hex(\random_bytes($length/2));
    }
}
