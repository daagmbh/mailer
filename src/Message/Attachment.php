<?php

namespace Daa\Library\Mail\Message;

class Attachment
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $contentType;

    /**
     * @var string
     */
    private $disposition = 'attachment';

    /**
     * Attachment constructor.
     *
     * @param string      $content
     * @param string|null $filename
     * @param string|null $contentType
     */
    public function __construct($content, $filename = null, $contentType = null)
    {
        $this->filename = $filename;
        $this->content = $content;
        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getDisposition()
    {
        return $this->disposition;
    }

    /**
     * @param string $disposition
     *
     * @return $this
     */
    public function setDisposition($disposition)
    {
        $this->disposition = $disposition;

        return $this;
    }
}
