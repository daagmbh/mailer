<?php

namespace Daa\Library\Mail\Message;

/**
 * This interface extends the MessageInterface with attachments.
 *
 * You can use the AttachmentMessageTrait for pre-configured methods.
 */
trait AttachmentMessageTrait
{
    /**
     * @var Attachment[]
     */
    private $attachments = [];

    /**
     * Returns the attachments of this mail.
     *
     * @return Attachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     *
     * @return $this
     */
    protected function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * @param Attachment $attachment
     *
     * @return $this
     */
    protected function addAttachment(Attachment $attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }
}
