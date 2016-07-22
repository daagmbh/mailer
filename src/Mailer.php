<?php

namespace Daa\Library\Mail;

use Daa\Library\Mail\Message\MailInterface;
use Daa\Library\Mail\Sender\NullSender;
use Daa\Library\Mail\Sender\SmtpSender;
use Daa\Library\Mail\TemplateRenderer\TemplateRendererInterface;
use Daa\Library\Mail\TemplateResolver\TemplateResolverInterface;
use Daa\Library\Mail\Transport\NullTransport;
use Daa\Library\Mail\Transport\SwiftMailTransport;
use Daa\Library\Mail\Transport\TransportInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * The mailer is responsible for the rendering and sending of messages.
 */
class Mailer extends AbstractMailer
{

    /**
     * @var TransportInterface[]
     */
    private $transports;

    /**
     * {@inheritDoc}
     */
    public function __construct(
        TemplateResolverInterface $templateResolver,
        TemplateRendererInterface $templateRenderer,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($templateResolver, $templateRenderer, $eventDispatcher);

        $this->registerTransport(SmtpSender::class, new SwiftMailTransport());
        $this->registerTransport(NullSender::class, new NullTransport());
    }

    /**
     * Add a transport for mails which have a sender of the given class.
     *
     * @param string             $senderClass
     * @param TransportInterface $transport
     *
     * @return void
     */
    public function registerTransport($senderClass, TransportInterface $transport)
    {
        $this->transports[$senderClass] = $transport;
    }

    /**
     * @param MailInterface $mail
     */
    public function sendMail(MailInterface $mail)
    {
        $this->eventDispatcher->dispatch(MailerEvents::beforeSending, new MailerEvent(null, $mail));

        $transport = $this->transports[get_class($mail->getSender())];
        $transport->sendMail($mail);

        $this->eventDispatcher->dispatch(MailerEvents::afterSending, new MailerEvent(null, $mail));
    }
}
