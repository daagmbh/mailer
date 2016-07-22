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
        $swiftMessage = Swift_Message::newInstance($mail->getSubject());
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
            $swiftAttachment = Swift_Attachment::newInstance(
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

        $transport = Swift_SmtpTransport::newInstance($sender->getHost(), $sender->getPort())
            ->setUsername($sender->getUser())
            ->setPassword($sender->getPassword())
            ->setEncryption($sender->getEncryption());

        $mailer = Swift_Mailer::newInstance($transport);

        return $mailer;
    }
}
