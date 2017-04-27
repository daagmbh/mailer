<?php

namespace Daa\Library\Mail\Transport;

use Daa\Library\Mail\Message\MailInterface;

/**
 * A transport is responsible for the actual sending of the mail.
 */
interface TransportInterface
{
    /**
     * Send the given mail.
     *
     * @param MailInterface $mail
     *
     * @return void
     */
    public function sendMail(MailInterface $mail);
}
