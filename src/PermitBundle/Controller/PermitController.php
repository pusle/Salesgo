<?php

namespace PermitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PermitBundle\Entity\Permit;
use PermitBundle\Form\PermitType;

/**
 * Permit controller.
 *
 * @Route("/permits")
 */
class PermitController extends Controller
{
    /**
     * Lists all Permit entities.
     *
     * @Route("/", name="permit_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $permits = $em->getRepository('AppBundle:Permit')->findAll();

        return $this->render('permit/index.html.twig', array(
            'permitDetails' => $permits,
        ));
    }

    /**
     * Creates a new Permit entity.
     *
     * @Route("/new", name="permit_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $permit = new Permit();
        $form = $this->createForm('AppBundle\Form\PermitType', $permit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($permit);
            $em->flush();

            return $this->redirectToRoute('permit_show', array('id' => $permit->getId()));
        }

        return $this->render('permit/new.html.twig', array(
            'permitDetail' => $permit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Permit entity.
     *
     * @Route("/{id}", name="permit_show")
     * @Method("GET")
     */
    public function showAction(Permit $permit)
    {
        $deleteForm = $this->createDeleteForm($permit);

        return $this->render('permit/show.html.twig', array(
            'permitDetail' => $permit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Permit entity.
     *
     * @Route("/{id}/edit", name="permit_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Permit $permit)
    {
        $deleteForm = $this->createDeleteForm($permit);
        $editForm = $this->createForm('AppBundle\Form\PermitType', $permit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($permit);
            $em->flush();

            return $this->redirectToRoute('permit_edit', array('id' => $permit->getId()));
        }

        return $this->render('permit/edit.html.twig', array(
            'permitDetail' => $permit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Permit entity.
     *
     * @Route("/{id}", name="permit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Permit $permit)
    {
        $form = $this->createDeleteForm($permit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($permit);
            $em->flush();
        }

        return $this->redirectToRoute('permit_index');
    }

    /**
     * Creates a form to delete a Permit entity.
     *
     * @param Permit $permit The Permit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Permit $permit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('permit_delete', array('id' => $permit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
