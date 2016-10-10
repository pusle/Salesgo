<?php

namespace LeadBundle\Event;

use AppBundle\Event\ContactCreatedEvent;
use Doctrine\ORM\EntityManager;
use LeadBundle\Entity\LeadStatus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContactSubscriber implements EventSubscriberInterface
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return array(
            ContactCreatedEvent::NAME => array('onContactCreated'),
        );
    }

    public function onContactCreated(ContactCreatedEvent $event)
    {
        // Get first LeadStatusType
        $leadStatusType = $this->em->getRepository('LeadBundle:LeadStatusType')->findOneBy(array('id' => 1));

        // Create LeadStatus
        $leadStatus = new LeadStatus();
        $leadStatus->setLeadStatusType($leadStatusType);
        $leadStatus->setContact($event->getContact());
        $leadStatus->setChangedAt(new \DateTime());

        // Add LeadStatus to contact
        $event->getContact()->addLeadStatus($leadStatus);
    }
}
