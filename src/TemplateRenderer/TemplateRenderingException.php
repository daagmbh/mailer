<?php

namespace Daa\Library\Mail\TemplateRenderer;

use Exception;
use LogicException;

/**
 * This exception is thrown if there was an error during the template rendering.
 */
class TemplateRenderingException extends LogicException
{
    /**
     * @param string    $templateKey
     * @param Exception $originalException
     *
     * @return static
     */
    public static function renderingFailed($templateKey, Exception $originalException)
    {
        $message = sprintf('Template rendering for %s failed: %s', $templateKey, $originalException->getMessage());

        return new static($message, $originalException->getCode(), $originalException);
    }
}
