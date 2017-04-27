<?php

namespace Daa\Library\Mail;

// @codingStandardsIgnoreFile

/**
 * This class contains constants for events that the mailer dispatches.
 */
final class MailerEvents
{
    const beforeRendering = 'mailer.before_rendering';
    const afterRendering = 'mailer.after_rendering';

    const beforeSending = 'mailer.before_sending';
    const afterSending = 'mailer.after_sending';
}
