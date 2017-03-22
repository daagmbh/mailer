<?php

namespace TemplateRenderer\Tests;

use Daa\Library\Mail\TemplateRenderer\TemplateRenderingException;
use Daa\Library\Mail\TemplateRenderer\TwigTemplateRenderer;
use PHPUnit\Framework\TestCase;

class TwigTemplateRendererTest extends TestCase
{
    /**
     * @var TwigTemplateRenderer
     */
    private $renderer;

    /**
     * Init the twig environment for the tests
     */
    public function setUp()
    {
        if (!class_exists(\Twig_Environment::class)) {
            $this->markTestSkipped('Twig not installed.');

            return;
        }

        $twig = new \Twig_Environment(new \Twig_Loader_Array());
        $this->renderer = new TwigTemplateRenderer($twig);
    }

    /**
     * @test
     */
    public function testTextRendering()
    {
        $rendered = $this->renderer->renderTextTemplate('mail.template', $this->getMailTemplate(), [
            'name' => 'World',
        ]);

        // HTML tags and indentations are removed
        $this->assertEquals("Hello World!\n\nThis is my super awesome message!", $rendered);
    }

    /**
     * @test
     */
    public function testHtmlRendering()
    {
        $rendered = $this->renderer->renderHtmlTemplate('mail.template', $this->getMailTemplate(), [
            'name' => 'World',
        ]);

        // Whitespaces at the beginning and end are trimmed, but everything else is kept
        $this->assertEquals('<strong>Hello World!</strong><br />
            <br />
            This is my super awesome message!', $rendered);
    }

    /**
     * @test
     */
    public function testRenderingError()
    {
        try {
            $this->renderer->renderHtmlTemplate('mail.template', '{{ name', []);
        } catch (TemplateRenderingException $e) {
            $this->assertTrue(strpos($e->getMessage(), 'Template rendering for mail.template failed: ') === 0);
        }
    }

    /**
     * @return string
     */
    private function getMailTemplate(): string
    {
        return '
            <strong>Hello {{ name }}!</strong><br />
            <br />
            {% if true -%}
                This is my super awesome message!
            {%- endif %}
        ';
    }
}
