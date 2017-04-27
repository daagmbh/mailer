<?php

namespace Daa\Library\Mail\TemplateRenderer;

interface TemplateRendererInterface
{
    /**
     * Render the template as text with the given parameters.
     *
     * @param string  $templateKey
     * @param string  $template
     * @param mixed[] $parameters
     *
     * @return string
     */
    public function renderTextTemplate($templateKey, $template, array $parameters);

    /**
     * Render the template as html with the given parameters.
     *
     * @param string  $templateKey
     * @param string  $template
     * @param mixed[] $parameters
     *
     * @return string
     */
    public function renderHtmlTemplate($templateKey, $template, array $parameters);
}
