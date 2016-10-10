<?php

namespace LeadBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use LeadBundle\Entity\LeadStatusType;
use LeadBundle\Form\LeadStatusTypeType;

/**
 * LeadStatusType controller.
 *
 * @Route("/leadstatustype")
 */
class LeadStatusTypeController extends Controller
{
    /**
     * Lists all LeadStatusType entities.
     *
     * @Route("/", name="leadstatustype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $leadStatusTypes = $em->getRepository('LeadBundle:LeadStatusType')->findAll();

        return $this->render('leadstatustype/index.html.twig', array(
            'leadStatusTypes' => $leadStatusTypes,
        ));
    }

    /**
     * Creates a new LeadStatusType entity.
     *
     * @Route("/new", name="leadstatustype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $leadStatusType = new LeadStatusType();
        $form = $this->createForm('LeadBundle\Form\LeadStatusTypeType', $leadStatusType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($leadStatusType);
            $em->flush();

            return $this->redirectToRoute('leadstatustype_show', array('id' => $leadStatusType->getId()));
        }

        return $this->render('leadstatustype/new.html.twig', array(
            'leadStatusType' => $leadStatusType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a LeadStatusType entity.
     *
     * @Route("/{id}", name="leadstatustype_show")
     * @Method("GET")
     */
    public function showAction(LeadStatusType $leadStatusType)
    {
        $deleteForm = $this->createDeleteForm($leadStatusType);

        return $this->render('leadstatustype/show.html.twig', array(
            'leadStatusType' => $leadStatusType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing LeadStatusType entity.
     *
     * @Route("/{id}/edit", name="leadstatustype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, LeadStatusType $leadStatusType)
    {
        $deleteForm = $this->createDeleteForm($leadStatusType);
        $editForm = $this->createForm('LeadBundle\Form\LeadStatusTypeType', $leadStatusType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($leadStatusType);
            $em->flush();

            return $this->redirectToRoute('leadstatustype_edit', array('id' => $leadStatusType->getId()));
        }

        return $this->render('leadstatustype/edit.html.twig', array(
            'leadStatusType' => $leadStatusType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a LeadStatusType entity.
     *
     * @Route("/{id}", name="leadstatustype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, LeadStatusType $leadStatusType)
    {
        $form = $this->createDeleteForm($leadStatusType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($leadStatusType);
            $em->flush();
        }

        return $this->redirectToRoute('leadstatustype_index');
    }

    /**
     * Creates a form to delete a LeadStatusType entity.
     *
     * @param LeadStatusType $leadStatusType The LeadStatusType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LeadStatusType $leadStatusType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('leadstatustype_delete', array('id' => $leadStatusType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
