<?php

namespace Daa\Library\Mail\Event;

use Daa\Library\Mail\Message\MailInterface;

/**
 * Event class with payload for events around the sending of a mail. This class never has a message object.
 */
class MailSendingEvent extends MailerEvent
{
    /**
     * @var bool
     */
    private $sendingStopped = false;

    /**
     * @param MailInterface $mail
     */
    public function __construct(MailInterface $mail)
    {
        parent::__construct(null, $mail);
    }

    /**
     * Prevent the mail from being sent.
     */
    public function stopSendingMail()
    {
        $this->sendingStopped = true;
    }

    /**
     * @return bool
     */
    public function isSendingStopped(): bool
    {
        return $this->sendingStopped;
    }
}
