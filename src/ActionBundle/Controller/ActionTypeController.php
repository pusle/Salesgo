<?php

namespace ActionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ActionBundle\Entity\ActionType;
use ActionBundle\Form\ActionTypeType;

/**
 * ActionType controller.
 *
 * @Route("/actiontype")
 */
class ActionTypeController extends Controller
{
    /**
     * Lists all ActionType entities.
     *
     * @Route("/", name="actiontype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $actionTypes = $em->getRepository('ActionBundle:ActionType')->findAll();

        return $this->render('actiontype/index.html.twig', array(
            'actionTypes' => $actionTypes,
        ));
    }

    /**
     * Creates a new ActionType entity.
     *
     * @Route("/new", name="actiontype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $actionType = new ActionType();
        $form = $this->createForm('ActionBundle\Form\ActionTypeType', $actionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($actionType);
            $em->flush();

            return $this->redirectToRoute('actiontype_show', array('id' => $actionType->getId()));
        }

        return $this->render('actiontype/new.html.twig', array(
            'actionType' => $actionType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ActionType entity.
     *
     * @Route("/{id}", name="actiontype_show")
     * @Method("GET")
     */
    public function showAction(ActionType $actionType)
    {
        $deleteForm = $this->createDeleteForm($actionType);

        return $this->render('actiontype/show.html.twig', array(
            'actionType' => $actionType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ActionType entity.
     *
     * @Route("/{id}/edit", name="actiontype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ActionType $actionType)
    {
        $deleteForm = $this->createDeleteForm($actionType);
        $editForm = $this->createForm('ActionBundle\Form\ActionTypeType', $actionType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($actionType);
            $em->flush();

            return $this->redirectToRoute('actiontype_edit', array('id' => $actionType->getId()));
        }

        return $this->render('actiontype/edit.html.twig', array(
            'actionType' => $actionType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ActionType entity.
     *
     * @Route("/{id}", name="actiontype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ActionType $actionType)
    {
        $form = $this->createDeleteForm($actionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($actionType);
            $em->flush();
        }

        return $this->redirectToRoute('actiontype_index');
    }

    /**
     * Creates a form to delete a ActionType entity.
     *
     * @param ActionType $actionType The ActionType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ActionType $actionType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('actiontype_delete', array('id' => $actionType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
