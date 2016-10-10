<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\IndustryCode;
use AppBundle\Form\IndustryCodeType;

/**
 * IndustryCode controller.
 *
 * @Route("/industrycode")
 */
class IndustryCodeController extends Controller
{
    /**
     * Lists all IndustryCode entities.
     *
     * @Route("/", name="industrycode_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $industryCodes = $em->getRepository('AppBundle:IndustryCode')->findAll();

        return $this->render('industrycode/index.html.twig', array(
            'industryCodes' => $industryCodes,
        ));
    }

    /**
     * Creates a new IndustryCode entity.
     *
     * @Route("/new", name="industrycode_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $industryCode = new IndustryCode();
        $form = $this->createForm('AppBundle\Form\IndustryCodeType', $industryCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($industryCode);
            $em->flush();

            return $this->redirectToRoute('industrycode_show', array('id' => $industryCode->getId()));
        }

        return $this->render('industrycode/new.html.twig', array(
            'industryCode' => $industryCode,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a IndustryCode entity.
     *
     * @Route("/{id}", name="industrycode_show")
     * @Method("GET")
     */
    public function showAction(IndustryCode $industryCode)
    {
        $deleteForm = $this->createDeleteForm($industryCode);

        return $this->render('industrycode/show.html.twig', array(
            'industryCode' => $industryCode,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing IndustryCode entity.
     *
     * @Route("/{id}/edit", name="industrycode_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, IndustryCode $industryCode)
    {
        $deleteForm = $this->createDeleteForm($industryCode);
        $editForm = $this->createForm('AppBundle\Form\IndustryCodeType', $industryCode);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($industryCode);
            $em->flush();

            return $this->redirectToRoute('industrycode_edit', array('id' => $industryCode->getId()));
        }

        return $this->render('industrycode/edit.html.twig', array(
            'industryCode' => $industryCode,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a IndustryCode entity.
     *
     * @Route("/{id}", name="industrycode_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, IndustryCode $industryCode)
    {
        $form = $this->createDeleteForm($industryCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($industryCode);
            $em->flush();
        }

        return $this->redirectToRoute('industrycode_index');
    }

    /**
     * Creates a form to delete a IndustryCode entity.
     *
     * @param IndustryCode $industryCode The IndustryCode entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(IndustryCode $industryCode)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('industrycode_delete', array('id' => $industryCode->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
