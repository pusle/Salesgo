<?php

namespace PermitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PermitBundle\Entity\PermitType;
use PermitBundle\Form\PermitTypeType;

/**
 * PermitType controller.
 *
 * @Route("/permittype")
 */
class PermitTypeController extends Controller
{
    /**
     * Lists all PermitType entities.
     *
     * @Route("/", name="permittype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $permitTypes = $em->getRepository('AppBundle:PermitType')->findAll();

        return $this->render('permittype/index.html.twig', array(
            'permitTypes' => $permitTypes,
        ));
    }

    /**
     * Creates a new PermitType entity.
     *
     * @Route("/new", name="permittype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $permitType = new PermitType();
        $form = $this->createForm('AppBundle\Form\PermitTypeType', $permitType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($permitType);
            $em->flush();

            return $this->redirectToRoute('permittype_show', array('id' => $permitType->getId()));
        }

        return $this->render('permittype/new.html.twig', array(
            'permitType' => $permitType,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PermitType entity.
     *
     * @Route("/{id}", name="permittype_show")
     * @Method("GET")
     */
    public function showAction(PermitType $permitType)
    {
        $deleteForm = $this->createDeleteForm($permitType);

        return $this->render('permittype/show.html.twig', array(
            'permitType' => $permitType,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PermitType entity.
     *
     * @Route("/{id}/edit", name="permittype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PermitType $permitType)
    {
        $deleteForm = $this->createDeleteForm($permitType);
        $editForm = $this->createForm('AppBundle\Form\PermitTypeType', $permitType);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($permitType);
            $em->flush();

            return $this->redirectToRoute('permittype_edit', array('id' => $permitType->getId()));
        }

        return $this->render('permittype/edit.html.twig', array(
            'permitType' => $permitType,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a PermitType entity.
     *
     * @Route("/{id}", name="permittype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PermitType $permitType)
    {
        $form = $this->createDeleteForm($permitType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($permitType);
            $em->flush();
        }

        return $this->redirectToRoute('permittype_index');
    }

    /**
     * Creates a form to delete a PermitType entity.
     *
     * @param PermitType $permitType The PermitType entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PermitType $permitType)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('permittype_delete', array('id' => $permitType->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
