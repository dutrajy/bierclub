<?php

namespace Commercial\Application\Financial;

use Commercial\Application\Common\Creatable;

class AccountOperation extends \Commercial\Framework\Database\Record
{
    /** @Property */
    protected $accountId;

    /** @Property */
    protected $amount;

    /** @Property */
    protected $description;

    /** @Property */
    protected $debitedFrom;

    protected $accountDebited;

    protected $account;

    use Creatable;

    public function getAccount() {
        if (!$this->account) {
            $this->account = Account::findOne(["id" => $this->getAccountId()]);
        }

        return $this->account;
    }

    public function setAccount($account)
    {
        $this->account = $account;
        $this->setAccountId($account->getId());
        return $this;
    }

    public function getAccountDebited()
    {
        if (!$this->accountDebited) {
            $this->accountDebited = Account::findOne(["id" => $this->getDebitedFrom()]);
        }

        return $this->accountDebited;
    }

    public function debitFrom($account)
    {
        $this->insert();

        $debitOperation = new static();
        $debitOperation->setAmount(-$this->getAmount());
        $debitOperation->setAccountId($account->getId());
        $debitOperation->setDescription("Transferred to {$this->getAccountId()}");
        $debitOperation->setCreator(($this->getCreator()));

        $debitOperation->insert();
    }
}
