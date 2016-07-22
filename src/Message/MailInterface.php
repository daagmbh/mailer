<?php

namespace Daa\Library\Mail\Message;

use Daa\Library\Mail\RecipientContainerInterface;
use Daa\Library\Mail\Sender\SenderInterface;

/**
 * A mail is a rendered message, i.e. a message that is able to be send (it has a rendered body, recipients etc.)
 */
interface MailInterface
{

    /**
     * @return SenderInterface
     */
    public function getSender();

    /**
     * @return RecipientContainerInterface
     */
    public function getRecipients();

    /**
     * Returns the translation key of the subject.
     * It will be translated with the help of the parameters from getParameters()
     *
     * @return string
     */
    public function getSubject();

    /**
     * Returns the text body of this mail.
     *
     * @return string
     */
    public function getBodyText();

    /**
     * Returns the html body of this mail.
     *
     * @return string
     */
    public function getBodyHtml();

    /**
     * Returns the attachments of this mail.
     *
     * @return Attachment[]
     */
    public function getAttachments();
}
