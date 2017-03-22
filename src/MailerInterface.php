<?php

namespace Daa\Library\Mail;

use Closure;
use Daa\Library\Mail\Message\MailInterface;
use Daa\Library\Mail\Message\MessageInterface;
use Daa\Library\Mail\Sender\SenderInterface;

/**
 * The mailer is responsible for sending message templates.
 */
interface MailerInterface
{

    /**
     * Get the sender object for the given id and locale.
     *
     * If the passed id is a closure, the closure is called with all available senders to find the relevant sender.
     *
     * @param string|Closure $id
     * @param string        $locale
     *
     * @return SenderInterface
     */
    public function getSender($id, $locale);

    /**
     * @param MessageInterface  $message
     *
     * @return void
     */
    public function sendMessage(MessageInterface $message);

    /**
     * @param MailInterface $mail
     *
     * @return void
     */
    public function sendMail(MailInterface $mail);

    /**
     * @param MessageInterface  $message
     *
     * @return MailInterface
     */
    public function renderMessage(MessageInterface $message);
}
