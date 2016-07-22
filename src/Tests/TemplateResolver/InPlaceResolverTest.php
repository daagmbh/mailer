<?php

namespace Daa\Library\Mail\Tests\TemplateResolver;

use Daa\Library\Mail\Message\MessageInterface;
use Daa\Library\Mail\TemplateResolver\InPlaceResolver;

class InPlaceResolverTest extends \PHPUnit_Framework_TestCase
{

    public function testResolver()
    {
        $resolver = new InPlaceResolver();

        $message = $this->getMock(MessageInterface::class);
        $this->assertEquals('Hello World', $resolver->resolveTemplate('Hello World', 'de_DE', $message));
    }
}
