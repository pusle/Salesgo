<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Contact;

class ContactCreatedEvent extends Event
{
    const NAME = 'contact.created';

    protected $contact;
    protected $em;

    public function __construct(Contact $contact, $em)
    {
        $this->contact = $contact;
        $this->em = $em;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function getEventManager()
    {
        return $this->em;
    }
}
