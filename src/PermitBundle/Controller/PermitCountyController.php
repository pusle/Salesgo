<?php

namespace PermitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PermitBundle\Entity\PermitCounty;
use PermitBundle\Form\PermitCountyType;

/**
 * PermitCounty controller.
 *
 * @Route("/permitcounty")
 */
class PermitCountyController extends Controller
{
    /**
     * Lists all PermitCounty entities.
     *
     * @Route("/", name="permitcounty_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $permitCounties = $em->getRepository('AppBundle:PermitCounty')->findAll();

        return $this->render('permitcounty/index.html.twig', array(
            'permitCounties' => $permitCounties,
        ));
    }

    /**
     * Creates a new PermitCounty entity.
     *
     * @Route("/new", name="permitcounty_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $permitCounty = new PermitCounty();
        $form = $this->createForm('AppBundle\Form\PermitCountyType', $permitCounty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($permitCounty);
            $em->flush();

            return $this->redirectToRoute('permitcounty_show', array('id' => $permitCounty->getId()));
        }

        return $this->render('permitcounty/new.html.twig', array(
            'permitCounty' => $permitCounty,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PermitCounty entity.
     *
     * @Route("/{id}", name="permitcounty_show")
     * @Method("GET")
     */
    public function showAction(PermitCounty $permitCounty)
    {
        $deleteForm = $this->createDeleteForm($permitCounty);

        return $this->render('permitcounty/show.html.twig', array(
            'permitCounty' => $permitCounty,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PermitCounty entity.
     *
     * @Route("/{id}/edit", name="permitcounty_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PermitCounty $permitCounty)
    {
        $deleteForm = $this->createDeleteForm($permitCounty);
        $editForm = $this->createForm('AppBundle\Form\PermitCountyType', $permitCounty);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($permitCounty);
            $em->flush();

            return $this->redirectToRoute('permitcounty_edit', array('id' => $permitCounty->getId()));
        }

        return $this->render('permitcounty/edit.html.twig', array(
            'permitCounty' => $permitCounty,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a PermitCounty entity.
     *
     * @Route("/{id}", name="permitcounty_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PermitCounty $permitCounty)
    {
        $form = $this->createDeleteForm($permitCounty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($permitCounty);
            $em->flush();
        }

        return $this->redirectToRoute('permitcounty_index');
    }

    /**
     * Creates a form to delete a PermitCounty entity.
     *
     * @param PermitCounty $permitCounty The PermitCounty entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PermitCounty $permitCounty)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('permitcounty_delete', array('id' => $permitCounty->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
