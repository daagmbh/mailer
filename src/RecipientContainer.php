<?php

namespace Daa\Library\Mail;

/**
 * A basic recipient container.
 */
class RecipientContainer implements RecipientContainerInterface
{

    /**
     * @var string[]
     */
    protected $to = [];

    /**
     * @var string[]
     */
    protected $cc = [];

    /**
     * @var string[]
     */
    protected $bcc = [];

    /**
     * @var string[]
     */
    protected $replyTo = [];

    /**
     * RecipientContainer constructor.
     *
     * @param string|string[] $to
     * @param string|string[] $cc
     * @param string|string[] $bcc
     * @param string|string[] $replyTo
     */
    public function __construct($to = [], $cc = [], $bcc = [], $replyTo = [])
    {
        $this->addTo($to);
        $this->addCc($cc);
        $this->addBcc($bcc);
        $this->addReplyTo($replyTo);
    }

    /**
     * Returns true if there is at least one recipient.
     *
     * @return bool
     */
    public function hasRecipients()
    {
        return !empty(array_merge($this->to, $this->cc, $this->bcc));
    }

    /**
     * Removes all recipients in this container.
     *
     * @return void
     */
    public function clearRecipients()
    {
        $this->to = [];
        $this->cc = [];
        $this->bcc = [];
        $this->replyTo = [];
    }

    /**
     * Returns the list of all recipients. The email address will be the key, the optional name is the value.
     *
     * @return string[]
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Add a further recipient. Instead of a email/name pair, also an array can be passed that maps an
     * email address to a name.
     *
     * @param string|string[]   $email
     * @param string|null       $name
     *
     * @return $this
     */
    public function addTo($email, $name = null)
    {
        return $this->addRecipients($this->to, $email, $name);
    }

    /**
     * Returns the list of all Cc-recipients. The email address will be the key, the optional name is the value.
     *
     * @return string[]
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param string|string[]   $email
     * @param string|null       $name
     *
     * @return $this
     */
    public function addCc($email, $name = null)
    {
        return $this->addRecipients($this->cc, $email, $name);
    }

    /**
     * Returns the list of all Bcc-recipients. The email address will be the key, the optional name is the value.
     *
     * @return string[]
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param string|string[]   $email
     * @param string|null $name
     *
     * @return $this
     */
    public function addBcc($email, $name = null)
    {
        return $this->addRecipients($this->bcc, $email, $name);
    }

    /**
     * Returns the list of all Reply-To-addresses. The email address will be the key, the optional name is the value.
     *
     * @return string[]
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param string      $email
     * @param string|null $name
     *
     * @return $this
     */
    public function addReplyTo($email, $name = null)
    {
        return $this->addRecipients($this->replyTo, $email, $name);
    }

    /**
     * Dynamically parses the passed recipients and adds them into the list.
     *
     * @param string[]          $list
     * @param string[]|string   $email
     * @param string|null       $name
     *
     * @return $this
     */
    protected function addRecipients(array &$list, $email, $name = null)
    {
        if (is_array($email)) {
            foreach ($email as $address => $name) {
                if (is_int($address)) {
                    // no key defined, the value ($name) contains the email address
                    $this->addRecipients($list, $name);

                    continue;
                }

                $this->addRecipients($list, $address, $name);
            }

            return $this;
        }

        $email = $this->convertIdn($email);
        $list[$email] = $name;

        return $this;
    }

    /**
     * Convert the given email address into IDNA ascii format.
     *
     * @param string $email
     *
     * @return string
     */
    private function convertIdn($email)
    {
        list($name, $domain) = explode('@', $email);
        $domain = idn_to_ascii($domain);
        $name = idn_to_ascii($name);

        return sprintf('%s@%s', $name, $domain);
    }
}
