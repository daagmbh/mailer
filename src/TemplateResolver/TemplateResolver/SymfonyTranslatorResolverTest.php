<?php

namespace Daa\Library\Mail\TemplateResolver\Tests;

use Daa\Library\Mail\Message\MessageInterface;
use Daa\Library\Mail\TemplateResolver\SymfonyTranslatorResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;

/**
 * Test the symfony translation resolver.
 */
class SymfonyTranslatorResolverTest extends TestCase
{

    /**
     * @var SymfonyTranslatorResolver
     */
    private $resolver;

    /**
     * @var MessageInterface
     */
    private $message;

    /**
     */
    public function setUp()
    {
        if (!class_exists(Translator::class)) {
            $this->markTestSkipped('Symfony translator not installed.');
            
            return;
        }

        $translator = new Translator('de_DE');
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', ['subject' => 'Hallo Welt'], 'de_DE');
        $translator->addResource('array', ['subject' => 'Hello World'], 'en_US');

        $this->resolver = new SymfonyTranslatorResolver($translator);
        $this->message = $this->createMock(MessageInterface::class);
    }

    /**
     * @test
     */
    public function testResolver()
    {
        $this->assertEquals('Hallo Welt', $this->resolver->resolveTemplate('subject', 'de_DE', $this->message));
        $this->assertEquals('Hello World', $this->resolver->resolveTemplate('subject', 'en_US', $this->message));
    }

    /**
     * @test
     * @expectedException \Daa\Library\Mail\TemplateResolver\UnresolvableException
     * @expectedExceptionMessage Key "missing" is not translated yet into locale de_DE.
     */
    public function testUnresolvable()
    {
        $this->resolver->resolveTemplate('missing', 'de_DE', $this->message);
    }
}
