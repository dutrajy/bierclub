<?php

namespace Commercial\Application\Common;

use Commercial\Application\Users\User;

trait Creatable
{
    /** @Property */
    protected $createdBy;

    protected $creator;

    public function getCreator()
    {
        if (!$this->creator) {
            $this->creator = User::findOne(["id" => $this->getCreatedBy()]);
        }

        return $this->creator;
    }

    public function setCreator($user)
    {
        $this->setCreatedBy($user->getId());
        $this->creator = User::findOne(["id" => $this->getCreatedBy()]);
        return $this;
    }
}
