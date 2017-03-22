<?php

namespace Daa\Library\Mail\Tests\Transport;

use Daa\Library\Mail\Message\Attachment;
use Daa\Library\Mail\Message\Mail;
use Daa\Library\Mail\RecipientContainer;
use Daa\Library\Mail\Sender\SmtpSender;
use Daa\Library\Mail\Transport\SwiftMailTransport;
use PHPUnit\Framework\TestCase;

/**
 * This class tests the SwiftMailTransport.
 */
class SwiftMailTransportTest extends TestCase
{
    /**
     * Skip tests if swift mailer is not installed
     */
    public function setUp()
    {
        if (!class_exists(\Swift_Message::class)) {
            $this->markTestSkipped('Swift mailer not installed.');

            return;
        }
    }

    /**
     * @test
     */
    public function testMailConversion()
    {
        $sender = new SmtpSender('foo.de', 'user', 'pass', 'foo@bar.de', 'foo');
        $recipients = (new RecipientContainer())
            ->addTo('john@doe.com')
            ->addTo('john2@doe.com')
            ->addCc('mail@gmail.com')
            ->addBcc('bcc@gmail.com')
            ->addReplyTo('no-reply@daa.net')
        ;

        $mail = (new Mail())
            ->setSubject('Example Subject')
            ->setBodyText('Hello World')
            ->setBodyHtml('<strong>Hello World</strong>')
            ->setRecipients($recipients)
            ->setSender($sender)
            ->addAttachment(new Attachment('hi', 'hi.txt', 'text/plain'))
        ;

        $transport = new SwiftMailTransport();
        $swiftMessage = $transport->convertMail($mail);

        $this->assertEquals($mail->getSubject(), $swiftMessage->getSubject());
        $this->assertEquals($mail->getRecipients()->getTo(), $swiftMessage->getTo());
        $this->assertEquals($mail->getRecipients()->getCc(), $swiftMessage->getCc());
        $this->assertEquals($mail->getRecipients()->getBcc(), $swiftMessage->getBcc());
        $this->assertEquals($mail->getRecipients()->getReplyTo(), $swiftMessage->getReplyTo());
        $this->assertEquals($mail->getBodyText(), $swiftMessage->getBody());

        // Assert html body is there
        $htmlPart = $swiftMessage->getChildren()[0];
        $this->assertEquals('text/html', $htmlPart->getContentType());
        $this->assertEquals($mail->getBodyHtml(), $htmlPart->getBody());

        // Assert attachment is there
        $attachmentPart = $swiftMessage->getChildren()[1];
        $this->assertEquals('text/plain', $attachmentPart->getContentType());
        $this->assertEquals('hi', $attachmentPart->getBody());
    }
}
