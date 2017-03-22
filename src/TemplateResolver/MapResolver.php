<?php

namespace Daa\Library\Mail\TemplateResolver;

use Daa\Library\Mail\Message\MessageInterface;

/**
 * This class contains a map of template keys to values.
 */
class MapResolver implements TemplateResolverInterface
{
    /**
     * @var array
     */
    private $templates;

    /**
     * @param string           $key
     * @param string           $locale
     * @param MessageInterface $message
     *
     * @return string
     */
    public function resolveTemplate($key, $locale, MessageInterface $message)
    {
        if (!isset($this->templates[$key][$locale])) {
            throw new UnresolvableException(sprintf('There is no template for key %s with locale %s', $key, $locale));
        }

        return $this->templates[$key][$locale];
    }

    /**
     * @param string $key
     * @param string $locale
     * @param string $template
     */
    public function addTemplate($key, $locale, $template)
    {
        $this->templates[$key][$locale] = $template;
    }
}
