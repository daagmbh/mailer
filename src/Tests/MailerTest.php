<?php

namespace Daa\Library\Mail\Tests;

use Daa\Library\Mail\Mailer;
use Daa\Library\Mail\Message\Attachment;
use Daa\Library\Mail\Message\AttachmentMessageInterface;
use Daa\Library\Mail\Message\AttachmentMessageTrait;
use Daa\Library\Mail\Message\Mail;
use Daa\Library\Mail\Message\Message;
use Daa\Library\Mail\RecipientContainer;
use Daa\Library\Mail\Sender\NullSender;
use Daa\Library\Mail\Sender\SenderInterface;
use Daa\Library\Mail\TemplateRenderer\TemplateRendererInterface;
use Daa\Library\Mail\TemplateResolver\TemplateResolverInterface;
use Daa\Library\Mail\Transport\TransportInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Tests the mailer.
 */
class MailerTest extends TestCase
{

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var TemplateResolverInterface|ObjectProphecy
     */
    private $templateResolver;

    /**
     * @var TemplateRendererInterface|ObjectProphecy
     */
    private $templateRenderer;

    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     *
     */
    public function setUp()
    {
        $this->templateResolver = $this->prophesize(TemplateResolverInterface::class);
        $this->templateRenderer = $this->prophesize(TemplateRendererInterface::class);
        $this->eventDispatcher = new EventDispatcher();

        $this->mailer = new Mailer(
            $this->templateResolver->reveal(),
            $this->templateRenderer->reveal(),
            $this->eventDispatcher
        );
    }

    /**
     * @test
     */
    public function testRendering()
    {
        $message = $this->createMessage();
        $subject = 'This is the subject!';

        // register a sender
        $sender = new NullSender();
        $this->mailer->registerSender('test', 'de_DE', $sender);

        // mock rendering
        $subjectRendererArguments = Argument::allOf(
            $messageArgument = Argument::withEntry('message', $message),
            $templateResolverArgument = Argument::withEntry('template_resolver', $this->templateResolver->reveal())
        );
        $renderParameterValidation = Argument::allOf(
            $messageArgument,
            $templateResolverArgument,
            Argument::withEntry('mail_subject', $subject)
        );

        // Prophecies
        $this->templateResolver->resolveTemplate('test.subject', 'de_DE', $message)->willReturn($subject);
        $this->templateResolver->resolveTemplate('test.template', 'de_DE', $message)->willReturn('hi');
        $this->templateRenderer->renderTextTemplate('test.subject', $subject, $subjectRendererArguments)->willReturn($subject);
        $this->templateRenderer->renderTextTemplate('test.template', 'hi', $renderParameterValidation)->willReturn('hi');
        $this->templateRenderer->renderHtmlTemplate('test.template', 'hi', $renderParameterValidation)->willReturn('<strong>hi</strong>');

        $mail = $this->mailer->renderMessage($message);
        $this->assertSame($sender, $mail->getSender());
        $this->assertSame($message->getRecipients(), $mail->getRecipients());
        $this->assertEquals('hi', $mail->getBodyText());
        $this->assertEquals('<strong>hi</strong>', $mail->getBodyHtml());
        $this->assertEquals($subject, $mail->getSubject());

        // Assert attachments
        $this->assertCount(1, $mail->getAttachments());
        $this->assertEquals('hello', $mail->getAttachments()[0]->getContent());
    }

    /**
     * @test
     */
    public function testTransmission()
    {
        $message = $this->createMessage();

        // mock transport
        $transport = $this->prophesize(TransportInterface::class);
        $transport->sendMail(Argument::type(Mail::class))->shouldBeCalled();

        // register sender
        $this->mailer->registerSender('test', 'de_DE', new NullSender());
        $this->mailer->registerTransport(NullSender::class, $transport->reveal());

        $this->mailer->sendMessage($message);
    }

    /**
     * @test
     * @expectedException           InvalidArgumentException
     * @expectedExceptionMessage    No sender registered with id "test" for locale "de_DE"
     */
    public function testSenderNotFoundException()
    {
        $message = $this->createMessage();

        $this->mailer->renderMessage($message);
        $this->fail('Exception was not thrown');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There is already a sender with id test for locale de_DE
     */
    public function testDuplicateSenderRegistrationFails()
    {
        // register a sender
        $sender = new NullSender();
        $this->mailer->registerSender('test', 'de_DE', $sender);
        $this->mailer->registerSender('test', 'de_DE', $sender);
    }

    /**
     * @return Message
     */
    protected function createMessage()
    {
        $recipients = new RecipientContainer('test@example.com');
        $message = new class('de_DE', 'test', $recipients, 'test.subject', 'test.template') extends Message
            implements AttachmentMessageInterface {
            use AttachmentMessageTrait;

            public function __construct(...$args) {
                parent::__construct(...$args);
                $this->addAttachment($attachment = new Attachment('hello'));
            }

        };

        return $message;
    }
}
