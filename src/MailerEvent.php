<?php

namespace Daa\Library\Mail;

use Daa\Library\Mail\Message\MailInterface;
use Daa\Library\Mail\Message\MessageInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event class with payload for mailer events. Depending on the event,
 * either the message or mail class (or both) are available.
 */
class MailerEvent extends Event
{

    /**
     * @var MessageInterface|null
     */
    private $message;

    /**
     * @var MailInterface|null
     */
    private $mail;

    /**
     * @param MessageInterface|null $message
     * @param MailInterface|null    $mail
     */
    public function __construct(MessageInterface $message = null, MailInterface $mail = null)
    {
        $this->message = $message;
        $this->mail = $mail;
    }

    /**
     * @return MessageInterface|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return MailInterface|null
     */
    public function getMail()
    {
        return $this->mail;
    }
}
