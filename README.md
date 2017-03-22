# DAA Mailer Library
The library eases the definition and sending of mails in multiple languages within an PHP application. It's main objective is to allow an easy mail sending without a lot of code. A mail is described in an abstract way in a so-called message which is rendered in multiple steps.

## Features
- Text & HTML Mails
- Automatic translation into the user's locale (including the sender's email address and name)
- Text can be fetched from an arbitrary resource / translator (currently, there is only one resolver for the Symfony translator)
- Templates can be rendered with an arbitrary template engine (currently, there is only a Twig renderer)
- Mails can be sent via various transport ways (currently, there is a transport via Swift Mailer)

## Usage
At first, you need to instantiate the mailer (here with Twig as renderer and Symfony translator as template resolver):
```php
$mailer = new DaaMailer(
	new SymfonyTemplateResolver($translator),
	new TwigTemplateRenderer($twig),
	$eventDispatcher
);
```
Afterwards, you can define senders that are used to send the message:
```php
// Here you define the login data, the sending email address and the name of the sender for a specific locae
$mailer->registerSender('support', 'de_DE', new SmtpSender('smtp.googlemail.com', 'foo@acme.com', 'password123', 'kundenservice@acme.com', 'Kundenservice'));
$mailer->registerSender('support', 'en_US', new SmtpSender('smtp.googlemail.com', 'foo@acme.com', 'password123', 'support@acme.com', 'Service'));
```

Then, you have to define a new message class. A message class is the abstract definition of a mail and is rendered by the mailer before sending.
```php
<?php

use Daa\Library\Mail\Message\MessageInterface;
use Daa\Library\Mail\RecipientContainer;

class RegistrationMessage implements MessageInterface
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getLocale()
    {
        return $this->user->getLocale();
    }

    public function getSenderId()
    {
        return 'support';
    }

    public function getRecipients()
    {
        return new RecipientContainer($this->user->getEmail());
    }

    public function getSubjectKey()
    {
        return 'user.registration.subject';
    }

    public function getTemplateKey()
    {
        return 'user.registration.text';
    }

    public function getParameters()
    {
        return ['user' => $this->user];
    }
}
```

At last, you only have to put the mail texts into your translation files (or in any other way the template resolver expects them).

And now, you can send an email:
```php
$message = new RegistrationMessage($user);
$mailer->sendMessage($message);
```

And that's it. Easy, huh?

## Advanced Usage
You can extend this library to allow some more advanced usages. For example, you can embedd your own template resolver and renderer.

## License

The MIT License (MIT). Please see License File for more information.
