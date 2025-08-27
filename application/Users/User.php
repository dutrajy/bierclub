<?php

namespace Commercial\Application\Users;

class User extends \Commercial\Framework\Database\Record
{
    /** @Property */
    protected $fullName;

    /** @Property */
    protected $role;

    /** @Property */
    protected $email;

    /** @Property */
    protected $username;

    /** @Property */
    protected $password;

    /** @Property */
    protected $card;
}
