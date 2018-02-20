<?php

namespace Daa\Library\Mail\Transport;

use Daa\Library\Mail\Message\MailInterface;
use Daa\Library\Mail\Sender\SmtpSender;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * Sends a mail with SwiftMail over SMTP.
 */
class SwiftMailTransport implements TransportInterface
{
    /**
     * Send the given mail.
     *
     * @param MailInterface $mail
     *
     * @return void
     */
    public function sendMail(MailInterface $mail)
    {
        $mailer = $this->createSwiftMailer($mail);
        $mailer->send($this->convertMail($mail));
    }

    /**
     * Converts the mail object into a Swift Message.
     *
     * @param MailInterface $mail
     *
     * @return \Swift_Message
     */
    public function convertMail(MailInterface $mail)
    {
        $swiftMessage = new Swift_Message($mail->getSubject());
        $swiftMessage->setFrom($mail->getSender()->getEmail(), $mail->getSender()->getName());

        $recipients = $mail->getRecipients();
        $swiftMessage->setTo($recipients->getTo());
        $swiftMessage->setCc($recipients->getCc());
        $swiftMessage->setBcc($recipients->getBcc());
        $swiftMessage->setReplyTo($recipients->getReplyTo());

        $swiftMessage->setBody($mail->getBodyText());

        if ($mail->getBodyHtml() !== null) {
            $swiftMessage->addPart($mail->getBodyHtml(), 'text/html');
        }

        foreach ($mail->getAttachments() as $attachment) {
            $swiftAttachment = new Swift_Attachment(
                $attachment->getContent(),
                $attachment->getFilename(),
                $attachment->getContentType()
            );
            $swiftAttachment->setDisposition($attachment->getDisposition());
            $swiftMessage->attach($swiftAttachment);
        }

        return $swiftMessage;
    }

    /**
     * @param MailInterface $mail
     *
     * @return Swift_Mailer
     */
    protected function createSwiftMailer(MailInterface $mail)
    {
        /** @var SmtpSender $sender */
        $sender = $mail->getSender();

        $transport = (new Swift_SmtpTransport($sender->getHost(), $sender->getPort()))
            ->setUsername($sender->getUser())
            ->setPassword($sender->getPassword())
            ->setEncryption($sender->getEncryption());

        $mailer = new Swift_Mailer($transport);

        return $mailer;
    }
}
