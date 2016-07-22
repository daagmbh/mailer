<?php

namespace Daa\Library\Mail\Message;

use Closure;
use Daa\Library\Mail\RecipientContainerInterface;

/**
 * A message describes an abstract mail that needs to be rendered before it can be send.
 */
interface MessageInterface
{

    /**
     * Returns the locale of this mail.
     *
     * @return string
     */
    public function getLocale();

    /**
     * Returns the identifier of the sender or a closure to find it.
     *
     * The sender defines the name and the email address of the sender. By returning a string a predefined sender
     * will be selected.
     *
     * In case a closure is returned, the closure gets all available senders passed via parameter and is required to
     * return a sender object.
     *
     * @return string|Closure
     */
    public function getSenderId();

    /**
     * Returns the recipients for this mail. The returned recipient container instance contains all types
     * of recipients (to, cc, bcc).
     *
     * @return RecipientContainerInterface
     */
    public function getRecipients();

    /**
     * Returns the translation key of the subject.
     * It will be translated with the help of the parameters from getParameters()
     *
     * @return string
     */
    public function getSubjectKey();

    /**
     * Returns the key of the template, which will be rendered by the template engine later.
     *
     * @return string
     */
    public function getTemplateKey();

    /**
     * Returns an array with all parameters that are necessary to render the mail.
     *
     * @return mixed[]
     */
    public function getParameters();
}
