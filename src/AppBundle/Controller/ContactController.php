<?php

namespace AppBundle\Controller;

use ActionBundle\Entity\Action;
use AppBundle\Entity\ContactMethod;
use AppBundle\Entity\Note;
use AppBundle\Event\ContactCreatedEvent;
use LeadBundle\Entity\LeadStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;

/**
 * Contact controller.
 *
 * @Route("/contact")
 */
class ContactController extends Controller
{
    /**
     * Lists all Contact entities.
     *
     * @Route("/", name="contact_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * Paginator
         */
        $page = $request->get('page') ? $request->get('page') : 1;
        $offset = $request->get('offset') ? $request->get('offset') : 30;

        $limit = $offset;
        $from = ($page * $offset) - $offset;

        $total = $em->getRepository('AppBundle:Contact')->findNumberOfContacts();

        $orderBy = $request->get('order');
        $orderDirection = $request->get('order_direction');
        $contacts = $em->getRepository('AppBundle:Contact')->findAllOrderedByPermits($from, $limit, $orderBy, $orderDirection);

        return $this->render('contact/index.html.twig', array(
            'contacts' => $contacts,
            'page' => $page,
            'offset' => $limit,
            'total' => $total
        ));
    }

    /**
     * Creates a new Contact entity.
     *
     * @Route("/new", name="contact_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createForm('AppBundle\Form\ContactType', $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Dispatcher
            $event = new ContactCreatedEvent($contact, $em);
            $eventDispatcher = $this->get('event_dispatcher');
            $eventDispatcher->dispatch(ContactCreatedEvent::NAME, $event);

            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('contact_show', array('id' => $contact->getId()));
        }

        return $this->render('contact/new.html.twig', array(
            'contact' => $contact,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Contact entity.
     *
     * @Route("/{id}", name="contact_show")
     * @Method("GET")
     */
    public function showAction(Contact $contact)
    {
        $deleteForm = $this->createDeleteForm($contact);

        $action = new Action();
        $action->setContact($contact);
        $actionForm = $this->createForm('ActionBundle\Form\ActionType', $action, array(
            'action' => $this->generateUrl('action_new', array('contact_id' => $contact->getId()))
        ));

        $note = new Note();
        $note->setContact($contact);
        $noteForm = $this->createForm('AppBundle\Form\NoteType', $note, array(
            'action' => $this->generateUrl('note_new', array('contact_id' => $contact->getId()))
        ));

        $contactMethod = new ContactMethod();
        $contactMethod->setContact($contact);
        $contactMethodForm = $this->createForm('AppBundle\Form\ContactMethodType', $contactMethod, array(
            'action' => $this->generateUrl('contactmethod_new', array('contact_id' => $contact->getId()))
        ));

        // Lead status form
        $leadStatus = new LeadStatus();
        $leadStatus->setContact($contact);
        $leadStatusForm = $this->createForm('LeadBundle\Form\LeadStatusType', $leadStatus, array(
            'action' => $this->generateUrl('leadstatus_new', array('contact_id' => $contact->getId()))
        ));

        return $this->render('contact/show.html.twig', array(
            'contact' => $contact,
            'delete_form' => $deleteForm->createView(),
            'action_form' => $actionForm->createView(),
            'note_form' => $noteForm->createView(),
            'contact_method_form' => $contactMethodForm->createView(),
            'lead_status_form' => $leadStatusForm->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Contact entity.
     *
     * @Route("/{id}/edit", name="contact_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Contact $contact)
    {
        $deleteForm = $this->createDeleteForm($contact);
        $editForm = $this->createForm('AppBundle\Form\ContactType', $contact);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('contact_edit', array('id' => $contact->getId()));
        }

        return $this->render('contact/edit.html.twig', array(
            'contact' => $contact,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Contact entity.
     *
     * @Route("/{id}", name="contact_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Contact $contact)
    {
        $form = $this->createDeleteForm($contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contact);
            $em->flush();
        }

        return $this->redirectToRoute('contact_index');
    }

    /**
     * Creates a form to delete a Contact entity.
     *
     * @param Contact $contact The Contact entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Contact $contact)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contact_delete', array('id' => $contact->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
