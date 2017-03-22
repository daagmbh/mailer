<?php

namespace Sender\Tests;

use Daa\Library\Mail\Sender\SmtpSender;
use PHPUnit\Framework\TestCase;

class SmtpSenderTest extends TestCase
{
    /**
     * @test
     */
    public function testSmtpSender()
    {
        $sender = new SmtpSender('smtp.gmail.com', 'info@example.com', '123456', 'info@acme.com', 'ACME Support');

        $this->assertEquals('smtp.gmail.com', $sender->getHost());
        $this->assertEquals('info@example.com', $sender->getUser());
        $this->assertEquals('123456', $sender->getPassword());
        $this->assertEquals('info@acme.com', $sender->getEmail());
        $this->assertEquals('ACME Support', $sender->getName());
        $this->assertEquals(25, $sender->getPort());
        $this->assertNull($sender->getEncryption());
    }

    /**
     * @test
     */
    public function testSmtpSenderWithEncryption()
    {
        $sender = new SmtpSender(
            'smtp.gmail.com',
            'info@example.com',
            '123456',
            'info@acme.com',
            'ACME Support',
            null,
            'tls'
        );

        $this->assertEquals(465, $sender->getPort());
        $this->assertEquals('tls', $sender->getEncryption());
    }

    /**
     * @test
     */
    public function testSmtpSenderWithEncryptionAndCustomPort()
    {
        $sender = new SmtpSender(
            'smtp.gmail.com',
            'info@example.com',
            '123456',
            'info@acme.com',
            'ACME Support',
            597,
            'tls'
        );

        $this->assertEquals(597, $sender->getPort());
        $this->assertEquals('tls', $sender->getEncryption());
    }
}
