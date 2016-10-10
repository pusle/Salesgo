<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ContactMethod;
use AppBundle\Form\ContactMethodType;

/**
 * ContactMethod controller.
 *
 * @Route("/contactmethod")
 */
class ContactMethodController extends Controller
{
    /**
     * Lists all ContactMethod entities.
     *
     * @Route("/", name="contactmethod_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contactMethods = $em->getRepository('AppBundle:ContactMethod')->findAll();

        return $this->render('contactmethod/index.html.twig', array(
            'contactMethods' => $contactMethods,
        ));
    }

    /**
     * Creates a new ContactMethod entity.
     *
     * @Route("/new/{contact_id}", name="contactmethod_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $contact_id)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:Contact')->findOneBy(array('id' => $contact_id));

        $contactMethod = new ContactMethod();
        $contactMethod->setContact($contact);

        $form = $this->createForm('AppBundle\Form\ContactMethodType', $contactMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contactMethod);
            $em->flush();

            return $this->redirectToRoute('contact_show', array('id' => $contact->getId()));
        }

        return $this->render('contactmethod/new.html.twig', array(
            'contactMethod' => $contactMethod,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ContactMethod entity.
     *
     * @Route("/{id}", name="contactmethod_show")
     * @Method("GET")
     */
    public function showAction(ContactMethod $contactMethod)
    {
        $deleteForm = $this->createDeleteForm($contactMethod);

        return $this->render('contactmethod/show.html.twig', array(
            'contactMethod' => $contactMethod,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ContactMethod entity.
     *
     * @Route("/{id}/edit", name="contactmethod_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ContactMethod $contactMethod)
    {
        $deleteForm = $this->createDeleteForm($contactMethod);
        $editForm = $this->createForm('AppBundle\Form\ContactMethodType', $contactMethod);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactMethod);
            $em->flush();

            return $this->redirectToRoute('contactmethod_edit', array('id' => $contactMethod->getId()));
        }

        return $this->render('contactmethod/edit.html.twig', array(
            'contactMethod' => $contactMethod,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ContactMethod entity.
     *
     * @Route("/{id}", name="contactmethod_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ContactMethod $contactMethod)
    {
        $form = $this->createDeleteForm($contactMethod);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contactMethod);
            $em->flush();
        }

        return $this->redirectToRoute('contact_show', array('id' => $contactMethod->getContact()->getId()));
    }

    /**
     * Creates a form to delete a ContactMethod entity.
     *
     * @param ContactMethod $contactMethod The ContactMethod entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ContactMethod $contactMethod)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('contactmethod_delete', array('id' => $contactMethod->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
