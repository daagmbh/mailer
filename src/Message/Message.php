<?php

namespace Daa\Library\Mail\Message;

use Daa\Library\Mail\RecipientContainerInterface;

/**
 * A basic message that can be configured with constructor arguments.
 */
class Message implements MessageInterface
{

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $senderId;

    /**
     * @var RecipientContainerInterface
     */
    protected $recipients;

    /**
     * @var string
     */
    protected $subjectKey;

    /**
     * @var string
     */
    protected $templateKey;

    /**
     * @var mixed[]
     */
    protected $parameters;

    /**
     * @param string                      $locale
     * @param string                      $senderId
     * @param RecipientContainerInterface $recipients
     * @param string                      $subjectKey
     * @param string                      $templateKey
     * @param mixed[]                     $parameters
     */
    public function __construct(
        $locale,
        $senderId,
        RecipientContainerInterface $recipients,
        $subjectKey,
        $templateKey,
        $parameters = []
    ) {
        $this->locale = $locale;
        $this->senderId = $senderId;
        $this->recipients = $recipients;
        $this->subjectKey = $subjectKey;
        $this->templateKey = $templateKey;
        $this->parameters = $parameters;
    }


    /**
     * Returns the locale of this mail.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Returns the identifier of the sender which is registered in the mailer. The sender defines the name
     * and the email address of the sender.
     *
     * @return string
     */
    public function getSenderId()
    {
        return $this->senderId;
    }

    /**
     * Returns the recipients for this mail. The returned recipient container instance contains all types
     * of recipients (to, cc, bcc).
     *
     * @return RecipientContainerInterface
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * Returns the translation key of the subject.
     * It will be translated with the help of the parameters from getParameters()
     *
     * @return string
     */
    public function getSubjectKey()
    {
        return $this->subjectKey;
    }

    /**
     * Returns the key of the template, which will be rendered by the template engine later.
     *
     * @return string
     */
    public function getTemplateKey()
    {
        return $this->templateKey;
    }

    /**
     * Returns an array with all parameters that are necessary to render the mail.
     *
     * @return mixed[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
