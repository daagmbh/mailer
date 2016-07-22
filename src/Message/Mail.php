<?php

namespace Daa\Library\Mail\Message;

use Daa\Library\Mail\RecipientContainerInterface;
use Daa\Library\Mail\Sender\SenderInterface;

class Mail implements MailInterface
{

    /**
     * @var SenderInterface
     */
    private $sender;

    /**
     * @var RecipientContainerInterface
     */
    private $recipients;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $bodyHtml;

    /**
     * @var string
     */
    private $bodyText;

    /**
     * @var Attachment[]
     */
    private $attachments = [];

    /**
     * @return SenderInterface
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param SenderInterface $sender
     *
     * @return $this
     */
    public function setSender(SenderInterface $sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return RecipientContainerInterface
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * @param RecipientContainerInterface $recipients
     *
     * @return $this
     */
    public function setRecipients(RecipientContainerInterface $recipients)
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getBodyHtml()
    {
        return $this->bodyHtml;
    }

    /**
     * @param string $bodyHtml
     *
     * @return $this
     */
    public function setBodyHtml($bodyHtml)
    {
        $this->bodyHtml = $bodyHtml;

        return $this;
    }

    /**
     * @return string
     */
    public function getBodyText()
    {
        return $this->bodyText;
    }

    /**
     * @param string $bodyText
     *
     * @return $this
     */
    public function setBodyText($bodyText)
    {
        $this->bodyText = $bodyText;

        return $this;
    }

    /**
     * @param array $attachments
     *
     * @return $this
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * @param Attachment $attachment
     *
     * @return $this
     */
    public function addAttachment(Attachment $attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Returns the attachments of this mail.
     *
     * @return Attachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }
}
