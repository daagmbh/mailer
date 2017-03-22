<?php

namespace Daa\Library\Mail\TemplateRenderer;

use Twig_Error;

/**
 * Render mail templates with twig.
 */
class TwigTemplateRenderer implements TemplateRendererInterface
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * TwigTemplateRenderer constructor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Render the template as text with the given parameters.
     *
     * @param string  $templateKey
     * @param string  $template
     * @param mixed[] $parameters
     *
     * @return string
     */
    public function renderTextTemplate($templateKey, $template, array $parameters)
    {
        $rendered =  $this->renderTemplate($templateKey, $template, $parameters, false);
        $rendered = strip_tags(html_entity_decode($rendered));
        $rendered = preg_replace("/\n( )+/", "\n", $rendered); // remove leading spaces

        return $rendered;
    }

    /**
     * Render the template as html with the given parameters.
     *
     * @param string  $templateKey
     * @param string  $template
     * @param mixed[] $parameters
     *
     * @return string
     */
    public function renderHtmlTemplate($templateKey, $template, array $parameters)
    {
        return $this->renderTemplate($templateKey, $template, $parameters, true);
    }

    /**
     * Render the template with the given parameters.
     *
     * @param string  $templateKey
     * @param string  $template
     * @param mixed[] $parameters
     *
     * @param bool    $isHtml
     *
     * @return string
     */
    protected function renderTemplate($templateKey, $template, array $parameters, $isHtml)
    {
        $parameters['is_html'] = $isHtml;

        try {
            $template = $this->twig->createTemplate($template)->render($parameters);
        } catch (Twig_Error $e) {
            throw TemplateRenderingException::renderingFailed($templateKey, $e);
        }

        return trim($template);
    }
}
