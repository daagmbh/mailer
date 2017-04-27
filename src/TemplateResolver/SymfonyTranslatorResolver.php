<?php

namespace Daa\Library\Mail\TemplateResolver;

use Daa\Library\Mail\Message\MessageInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * This class resolves mail templates with the Symfony translator.
 */
class SymfonyTranslatorResolver implements TemplateResolverInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $domain;

    /**
     * @param TranslatorInterface $translator
     * @param string|null         $domain
     */
    public function __construct(TranslatorInterface $translator, $domain = null)
    {
        $this->translator = $translator;
        $this->domain = $domain;
    }

    /**
     * Resolve the template by the given translation key.
     *
     * @param string           $key
     * @param string           $locale
     * @param MessageInterface $message
     *
     * @return string
     */
    public function resolveTemplate($key, $locale, MessageInterface $message)
    {
        $translation = $this->translator->trans($key, [], $this->domain, $locale);
        if ($translation === $key) {
            // no translation found
            throw new UnresolvableException(
                sprintf('Key "%s" is not translated yet into locale %s.', $key, $locale)
            );
        }

        return $translation;
    }
}
