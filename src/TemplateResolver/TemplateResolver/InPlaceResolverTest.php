<?php

namespace Daa\Library\Mail\TemplateResolver\Tests;

use Daa\Library\Mail\Message\MessageInterface;
use Daa\Library\Mail\TemplateResolver\InPlaceResolver;
use PHPUnit\Framework\TestCase;

class InPlaceResolverTest extends TestCase
{

    public function testResolver()
    {
        $resolver = new InPlaceResolver();

        $message = $this->createMock(MessageInterface::class);
        $this->assertEquals('Hello World', $resolver->resolveTemplate('Hello World', 'de_DE', $message));
    }
}
