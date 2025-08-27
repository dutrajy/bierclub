<?php

namespace Commercial\Application\Financial;

use Commercial\Application\Users\User;
use Commercial\Application\Bierclub\Member;

class Account extends \Commercial\Application\Common\Base
{
    /** @Property */
    protected $ownerType;

    /** @Property */
    protected $ownerId;

    /** @Property */
    protected $description;

    protected $owner;

    public function getOwner()
    {
        if (!$this->owner) {
            if ($this->getOwnerType() === "users") {
                $this->owner = User::findOne(["id" => $this->getOwnerId()]);
            } elseif ($this->getOwnerType() === "bierclub_members") {
                $this->owner = Member::findOne(["id" => $this->getOwnerId()]);
            }
        }

        return $this->owner;
    }

    public function setOwner($owner)
    {
        return $this->setOwnerType($owner::getTable())->setOwnerId($owner->getId());
    }

    public function getOperations()
    {
        return AccountOperation::findAll(["account_id" => $this->getId()]);
    }

    public function getBalance()
    {
        $sum = 0;

        foreach ($this->getOperations() as $operation) {
            $sum += $operation->getAmount();
        }

        return $sum;
    }
}
