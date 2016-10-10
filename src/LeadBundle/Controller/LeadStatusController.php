<?php

namespace LeadBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use LeadBundle\Entity\LeadStatus;
use LeadBundle\Form\LeadStatusType;

/**
 * LeadStatus controller.
 *
 * @Route("/leadstatus")
 */
class LeadStatusController extends Controller
{
    /**
     * Lists all LeadStatus entities.
     *
     * @Route("/", name="leadstatus_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $leadStatuses = $em->getRepository('LeadBundle:LeadStatus')->findAll();

        return $this->render('leadstatus/index.html.twig', array(
            'leadStatuses' => $leadStatuses,
        ));
    }

    /**
     * Creates a new LeadStatus entity.
     *
     * @Route("/new/{contact_id}", name="leadstatus_new", requirements={"contact_id": "\d+"}, defaults={"contact_id" = 1})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $contact_id)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:Contact')->findOneBy(array('id' => $contact_id));

        $leadStatus = new LeadStatus();
        $leadStatus->setChangedAt(new \DateTime());
        $leadStatus->setContact($contact);

        $form = $this->createForm('LeadBundle\Form\LeadStatusType', $leadStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($leadStatus);
            $em->flush();

            return $this->redirectToRoute('contact_show', array('id' => $contact->getId()));
        }

        return $this->render('@leadstatus/new.html.twig', array(
            'leadStatus' => $leadStatus,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a LeadStatus entity.
     *
     * @Route("/{id}", name="leadstatus_show")
     * @Method("GET")
     */
    public function showAction(LeadStatus $leadStatus)
    {
        $deleteForm = $this->createDeleteForm($leadStatus);

        return $this->render('leadstatus/show.html.twig', array(
            'leadStatus' => $leadStatus,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing LeadStatus entity.
     *
     * @Route("/{id}/edit", name="leadstatus_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, LeadStatus $leadStatus)
    {
        $deleteForm = $this->createDeleteForm($leadStatus);
        $editForm = $this->createForm('LeadBundle\Form\LeadStatusType', $leadStatus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($leadStatus);
            $em->flush();

            return $this->redirectToRoute('leadstatus_edit', array('id' => $leadStatus->getId()));
        }

        return $this->render('leadstatus/edit.html.twig', array(
            'leadStatus' => $leadStatus,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a LeadStatus entity.
     *
     * @Route("/{id}", name="leadstatus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, LeadStatus $leadStatus)
    {
        $form = $this->createDeleteForm($leadStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($leadStatus);
            $em->flush();
        }

        return $this->redirectToRoute('leadstatus_index');
    }

    /**
     * Creates a form to delete a LeadStatus entity.
     *
     * @param LeadStatus $leadStatus The LeadStatus entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LeadStatus $leadStatus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('leadstatus_delete', array('id' => $leadStatus->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
