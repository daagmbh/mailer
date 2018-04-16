<?php

namespace Daa\Library\Mail\Tests;

use Daa\Library\Mail\RecipientContainer;
use PHPUnit\Framework\TestCase;

class RecipientContainerTest extends TestCase
{
    /**
     * @test
     */
    public function testDefaultConstructor()
    {
        $recipients = new RecipientContainer();

        $this->assertEquals([], $recipients->getTo());
        $this->assertEquals([], $recipients->getCc());
        $this->assertEquals([], $recipients->getBcc());
        $this->assertEquals([], $recipients->getReplyTo());
        $this->assertFalse($recipients->hasRecipients());
    }

    /**
     * Test the constructor with string values.
     *
     * @test
     */
    public function testConstructor_String()
    {
        $recipients = new RecipientContainer(
            'test@example.com',
            'cc@example.com',
            'bcc@example.com',
            'reply@example.com'
        );

        $this->assertEquals(['test@example.com' => null], $recipients->getTo());
        $this->assertEquals(['cc@example.com' => null], $recipients->getCc());
        $this->assertEquals(['bcc@example.com' => null], $recipients->getBcc());
        $this->assertEquals(['reply@example.com' => null], $recipients->getReplyTo());
        $this->assertTrue($recipients->hasRecipients());
    }

    /**
     * Test the constructor with array values
     *
     * @test
     */
    public function testConstructor_Array()
    {
        $recipients = new RecipientContainer(
            ['test@example.com', 'a@example.com' => 'Herr A'],
            ['cc@example.com', 'b@example.com' => 'Herr B'],
            ['bcc@example.com', 'c@example.com' => 'Herr C'],
            ['reply@example.com', 'd@example.com' => 'Herr D']
        );

        $this->assertEquals(['test@example.com' => null, 'a@example.com' => 'Herr A'], $recipients->getTo());
        $this->assertEquals(['cc@example.com' => null, 'b@example.com' => 'Herr B'], $recipients->getCc());
        $this->assertEquals(['bcc@example.com' => null, 'c@example.com' => 'Herr C'], $recipients->getBcc());
        $this->assertEquals(['reply@example.com' => null, 'd@example.com' => 'Herr D'], $recipients->getReplyTo());
    }

    /**
     * @test
     */
    public function testAddTo()
    {
        $this->performAddMethod('To');
    }

    /**
     * @test
     */
    public function testAddCc()
    {
        $this->performAddMethod('Cc');
    }

    /**
     * @test
     */
    public function testAddBcc()
    {
        $this->performAddMethod('Bcc');
    }

    /**
     * @test
     */
    public function testAddReplyTo()
    {
        $this->performAddMethod('ReplyTo');
    }

    /**
     * @test
     */
    public function testClearRecipients()
    {
        $recipients = new RecipientContainer('example@foo.de', 'example@foo.de', 'example@foo.de', 'example@foo.de');

        $this->assertTrue($recipients->hasRecipients());

        $recipients->clearRecipients();
        $this->assertFalse($recipients->hasRecipients());
    }

    /**
     * @test
     */
    public function testTestIdnDomains()
    {
        $recipients = new RecipientContainer('info@hello-wööörld.de', 'wööörld@hello-wööörld.de');

        $this->assertEquals(['info@xn--hello-wrld-kcbaa.de' => null], $recipients->getTo());
        $this->assertEquals(['wööörld@xn--hello-wrld-kcbaa.de' => null], $recipients->getCc());
    }

    /**
     * Performs the tests for a specific recipient type.
     *
     * @param string $type
     */
    private function performAddMethod($type)
    {
        $addMethod = 'add' . $type;
        $getMethod = 'get' . $type;

        $recipients = new RecipientContainer();

        call_user_func([$recipients, $addMethod], 'foo@bar.de', 'Foo Bar');
        call_user_func([$recipients, $addMethod], 'example@example.com');
        call_user_func([$recipients, $addMethod], ['a@example.com']);

        $this->assertEquals(
            ['foo@bar.de' => 'Foo Bar', 'example@example.com' => null, 'a@example.com' => null],
            call_user_func([$recipients, $getMethod])
        );
    }
}
