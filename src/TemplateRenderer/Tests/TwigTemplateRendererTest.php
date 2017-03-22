<?php

namespace TemplateRenderer\Tests;

use Daa\Library\Mail\Message\MessageInterface;
use Daa\Library\Mail\TemplateRenderer\TemplateRenderingException;
use Daa\Library\Mail\TemplateRenderer\Twig\Extension\MailPartialExtension;
use Daa\Library\Mail\TemplateRenderer\TwigTemplateRenderer;
use Daa\Library\Mail\TemplateResolver\InPlaceResolver;
use Daa\Library\Mail\TemplateResolver\MapResolver;
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
        $twig->addExtension(new MailPartialExtension());
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
     * @test
     */
    public function testIncludingPartial()
    {
        $templateResolver = new MapResolver();
        $templateResolver->addTemplate('other_template', 'de_DE', '
        {% if true %}
            Hi PHP
        {% endif %}');

        $rendered = $this->renderer->renderTextTemplate('mail.template', '{{ partial("other_template") }}', [
            // The following properties are normally set by the mailer
            'message' => $this->prophesize(MessageInterface::class)->reveal(),
            'locale' => 'de_DE',
            'template_resolver' => $templateResolver,
        ]);
        $this->assertEquals('Hi PHP', $rendered);
    }

    /**
     * @test
     */
    public function testIncludingPartial_errorHandling()
    {
        $resolver = new MapResolver();
        $resolver->addTemplate('other_template', 'de_DE', '{{ error');

        try {
            $this->renderer->renderTextTemplate('mail.template', '{{ partial("other_template") }}', [
                // The following properties are normally set by the mailer
                'message' => $this->prophesize(MessageInterface::class)->reveal(),
                'locale' => 'de_DE',
                'template_resolver' => $resolver,
            ]);
        } catch (TemplateRenderingException $e) {
            $this->assertTrue(strpos($e->getMessage(), 'Template rendering for mail.template failed: An exception has been thrown during the rendering of a template ("Template rendering for other_template failed: Unexpected token "end of template" of value ""') === 0);
        }
    }

    /**
     * @test
     */
    public function testIncludingOptionalPartial()
    {
        $templateResolver = new MapResolver();
        $templateResolver->addTemplate('other_template', 'de_DE', '
        {% if true -%}
            Hi PHP
        {%- endif %}');

        $rendered = $this->renderer->renderTextTemplate(
            'mail.template',
            '{{ partial_if_exists("other_template") }}, {{ partial_if_exists("non_existing_template") }}',
            [
                // The following properties are normally set by the mailer
                'message' => $this->prophesize(MessageInterface::class)->reveal(),
                'locale' => 'de_DE',
                'template_resolver' => $templateResolver,
            ]
        );
        $this->assertEquals('Hi PHP,', $rendered);
    }

    /**
     * @test
     */
    public function testIncludingStringPartial()
    {
        $rendered = $this->renderer->renderTextTemplate(
            'mail.template',
            '{{ partial_string("{% if true %}Hi Partial{% endif %}") }}',
            [
            // The following properties are normally set by the mailer
            'message' => $this->prophesize(MessageInterface::class)->reveal(),
            'locale' => 'de_DE',
            'template_resolver' => new InPlaceResolver(),
        ]);
        $this->assertEquals('Hi Partial', $rendered);
    }

    /**
     * @test
     */
    public function testIncludingStringPartial_errorHandling()
    {
        try {
            $this->renderer->renderTextTemplate(
                'mail.template',
                '{{ partial_string("{% if true %}Hi Partial") }}',
                [
                    // The following properties are normally set by the mailer
                    'message' => $this->prophesize(MessageInterface::class)->reveal(),
                    'locale' => 'de_DE',
                    'template_resolver' => new InPlaceResolver(),
                ]);
        } catch (TemplateRenderingException $e) {
            $this->assertTrue(strpos($e->getMessage(), 'Template rendering for mail.template failed: An exception has been thrown during the rendering of a template ("Template rendering for {% if true %}Hi Partial failed: Unexpected end of template in') === 0);
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
