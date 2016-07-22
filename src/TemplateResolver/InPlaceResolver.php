<?php

namespace Daa\Library\Mail\TemplateResolver;

use Daa\Library\Mail\Message\MessageInterface;

/**
 * This class returns the in-place template of a message.
 */
class InPlaceResolver implements TemplateResolverInterface
{

    /**
     * @param string           $template
     * @param string           $locale
     * @param MessageInterface $message
     *
     * @return string
     */
    public function resolveTemplate($template, $locale, MessageInterface $message)
    {
        return $template;
    }
}
