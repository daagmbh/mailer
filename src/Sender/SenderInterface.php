<?php

namespace Daa\Library\Mail\Sender;

/**
 * A sender describes the details about the mail sender which are the translation keys for the sender's name and email
 * and the login data for the actual mail sender. An instance of this interface will be passed to the transport.
 * Furthermore, the name and email will be resolved and applied to the message / mail.
 */
interface SenderInterface
{

    /**
     * Returns the translation key of the sender's name.
     *
     * @return string|null
     */
    public function getName();

    /**
     * Returns the translation key of the sender's email.
     *
     * @return string
     */
    public function getEmail();
}
