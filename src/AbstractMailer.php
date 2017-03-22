<?php

namespace Daa\Library\Mail;

use Closure;
use Daa\Component\Common\Mail\Marker\SkipTemplateInterface;
use Daa\Library\Mail\Message\AttachmentMessageInterface;
use Daa\Library\Mail\Message\Mail;
use Daa\Library\Mail\Message\MailInterface;
use Daa\Library\Mail\Message\MessageInterface;
use Daa\Library\Mail\Sender\SenderInterface;
use Daa\Library\Mail\TemplateRenderer\TemplateRendererInterface;
use Daa\Library\Mail\TemplateResolver\TemplateResolverInterface;
use InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * The AbstractMailer defines some generic mailer functionalities that can be used by specific implementations.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class AbstractMailer implements MailerInterface
{

    /**
     * Contains all senders grouped by id and locale.
     *
     * @var SenderInterface[][]
     */
    protected $senders = [];

    /**
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * @var TemplateResolverInterface
     */
    protected $templateResolver;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param TemplateResolverInterface $templateResolver
     * @param TemplateRendererInterface $templateRenderer
     * @param EventDispatcherInterface  $eventDispatcher
     */
    public function __construct(
        TemplateResolverInterface $templateResolver,
        TemplateRendererInterface $templateRenderer,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->templateResolver = $templateResolver;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Add a new sender that is able to send the mail.
     *
     * @param string                $id
     * @param string                $locale
     * @param SenderInterface       $sender
     */
    public function registerSender($id, $locale, SenderInterface $sender)
    {
        if (isset($this->senders[$id][$locale])) {
            throw new InvalidArgumentException(
                sprintf('There is already a sender with id %s for locale %s', $id, $locale)
            );
        }

        $this->senders[$id][$locale] = $sender;
    }

    /**
     * Get the sender object for the given id and locale.
     *
     * If the passed id is a closure, the closure is called with all available senders to find the relevant sender.
     *
     * @param string|Closure $id
     * @param string        $locale
     *
     * @return SenderInterface
     */
    public function getSender($id, $locale)
    {
        if ($id instanceof Closure) {
            return $id($this->senders);
        }

        if (!isset($this->senders[$id][$locale])) {
            throw new InvalidArgumentException(
                sprintf('No sender registered with id "%s" for locale "%s"', $id, $locale)
            );
        }

        return $this->senders[$id][$locale];
    }

    /**
     * @param MessageInterface $message
     *
     * @return void
     */
    public function sendMessage(MessageInterface $message)
    {
        $mail = $this->renderMessage($message);

        $this->sendMail($mail);
    }

    /**
     * @param MessageInterface $message
     *
     * @return MailInterface
     */
    public function renderMessage(MessageInterface $message)
    {
        $this->eventDispatcher->dispatch(MailerEvents::beforeRendering, new MailerEvent($message));

        $mail = new Mail();
        $mail
            ->setSender($this->getSender($message->getSenderId(), $message->getLocale()))
            ->setRecipients($message->getRecipients());
        $this->renderTemplate($mail, $message);

        if ($message instanceof AttachmentMessageInterface) {
            $mail->setAttachments($message->getAttachments());
        }

        $this->eventDispatcher->dispatch(MailerEvents::afterRendering, new MailerEvent($message, $mail));

        return $mail;
    }

    /**
     * @param Mail                     $mail
     * @param MessageInterface $message
     */
    protected function renderTemplate(Mail $mail, MessageInterface $message)
    {
        // build parameters
        $parameters = $message->getParameters();
        $parameters['locale'] = $message->getLocale();
        $parameters['message'] = $message;
        $parameters['template_resolver'] = $this->templateResolver;

        // render subject at first
        $mail->setSubject($this->generateSubject($message, $parameters));

        // then render the body
        $parameters['mail_subject'] = $mail->getSubject();

        $template = $this->templateResolver->resolveTemplate(
            $message->getTemplateKey(),
            $message->getLocale(),
            $message
        );
        $renderedHtml = $this->templateRenderer->renderHtmlTemplate($message->getTemplateKey(), $template, $parameters);
        $renderedText = $this->templateRenderer->renderTextTemplate($message->getTemplateKey(), $template, $parameters);

        $mail->setBodyHtml($renderedHtml);
        $mail->setBodyText($renderedText);
    }

    /**
     * Generate and return the subject of the mail.
     *
     * @param MessageInterface $message
     * @param array            $parameters
     *
     * @return string
     */
    protected function generateSubject(MessageInterface $message, array $parameters)
    {
        $subjectTemplate = $this->templateResolver->resolveTemplate(
            $message->getSubjectKey(),
            $message->getLocale(),
            $message
        );

        return $this->templateRenderer->renderTextTemplate($message->getSubjectKey(), $subjectTemplate, $parameters);
    }
}
