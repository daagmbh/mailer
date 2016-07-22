<?php

namespace Daa\Library\Mail\Sender;

class NullSender implements SenderInterface
{

    /**
     * Returns the translation key of the sender's name.
     *
     * @return string|null
     */
    public function getName()
    {
        return null;
    }

    /**
     * Returns the translation key of the sender's email.
     *
     * @return string
     */
    public function getEmail()
    {
        return null;
    }
}
