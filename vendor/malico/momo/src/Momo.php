<?php

namespace Malico\Momo;

use JsonSerializable;
use Malico\Momo\Support\Traits\Functions;
use Malico\Momo\Support\Traits\Variables;

class Momo implements JsonSerializable
{
    use Variables;
    use Functions;

    /**
     * Set Email.
     *
     * @param string $email
     *
     * @return $this
     */
    public function email($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Set Amount.
     *
     * @param int $amount
     *
     * @return void
     */
    public function amount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Telephone.
     *
     * @param string|int $tel
     *
     * @return void
     */
    public function tel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Same as Tel.
     *
     * @param string $tel
     *
     * @return void
     */
    public function phone($tel)
    {
        $this->tel($tel);

        return $this;
    }
}
