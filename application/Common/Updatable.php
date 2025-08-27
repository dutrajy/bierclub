<?php

namespace Commercial\Application\Common;

use Commercial\Application\Users\User;

trait Updatable
{
    /** @Property */
    protected $updatedBy;

    protected $updater;

    public function getUpdater()
    {
        if (!$this->updater) {
            $this->updater = User::findOne(["id" => $this->getUpdatedBy()]);
        }

        return $this->updater;
    }

    public function setUpdater($user)
    {
        $this->setUpdatedBy($user->getId());
        $this->updater = User::findOne(["id" => $this->getUpdatedBy()]);
        return $this;
    }
}
