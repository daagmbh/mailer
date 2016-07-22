<?php

namespace Daa\Library\Mail\Message;

/**
 * This interface extends the MessageInterface with attachments.
 *
 * You can use the AttachmentMessageTrait for pre-configured methods.
 */
interface AttachmentMessageInterface extends MessageInterface
{

    /**
     * Returns the attachments of this mail.
     *
     * @return Attachment[]
     */
    public function getAttachments();
}
