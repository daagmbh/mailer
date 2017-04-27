<?php

namespace Daa\Library\Mail;

/**
 * An instance of this interface knows all recipients for a message.
 */
interface RecipientContainerInterface
{
    /**
     * Returns true if there is at least one recipient.
     *
     * @return bool
     */
    public function hasRecipients();

    /**
     * Removes all recipients in this container.
     *
     * @return void
     */
    public function clearRecipients();

    /**
     * Returns the list of all recipients. The email address will be the key, the optional name is the value.
     *
     * @return string[]
     */
    public function getTo();

    /**
     * @param string        $email
     * @param string|null   $name
     *
     * @return $this
     */
    public function addTo($email, $name = null);

    /**
     * Returns the list of all Cc-recipients. The email address will be the key, the optional name is the value.
     *
     * @return string[]
     */
    public function getCc();

    /**
     * @param string        $email
     * @param string|null   $name
     *
     * @return $this
     */
    public function addCc($email, $name = null);

    /**
     * Returns the list of all Bcc-recipients. The email address will be the key, the optional name is the value.
     *
     * @return string[]
     */
    public function getBcc();

    /**
     * @param string        $email
     * @param string|null   $name
     *
     * @return $this
     */
    public function addBcc($email, $name = null);

    /**
     * Returns the list of all Reply-To-addresses. The email address will be the key, the optional name is the value.
     *
     * @return string[]
     */
    public function getReplyTo();

    /**
     * @param string        $email
     * @param string|null   $name
     *
     * @return $this
     */
    public function addReplyTo($email, $name = null);
}
