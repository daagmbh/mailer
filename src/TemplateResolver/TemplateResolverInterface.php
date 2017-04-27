<?php

namespace Daa\Library\Mail\TemplateResolver;

use Daa\Library\Mail\Message\MessageInterface;

/**
 * A template resolver is responsible for the loading of the actual mail template.
 */
interface TemplateResolverInterface
{
    /**
     * Resolve the given template into the given locale. Additionally, the message object is passed
     * to allow different translations based on a specific message.
     *
     * @param string           $key
     * @param string           $locale
     * @param MessageInterface $message
     *
     * @return string
     */
    public function resolveTemplate($key, $locale, MessageInterface $message);
}
