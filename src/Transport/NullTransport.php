<?php

namespace Daa\Library\Mail\Transport;

use Daa\Library\Mail\Message\MailInterface;

/**
 * Does not send the email but drops it.
 */
class NullTransport implements TransportInterface
{
    /**
     * Send the given mail.
     *
     * @param MailInterface $mail
     *
     * @return void
     */
    public function sendMail(MailInterface $mail)
    {
    }
}
