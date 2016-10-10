<?php

namespace SecurityBundle\Controller;

use SecurityBundle\Form\UserType;
use SecurityBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function indexAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Create registration form
        $user = new User();
        $registrationForm = $this->createForm(UserType::class, $user, array(
            'action' => $this->generateUrl('user_registration')
        ));

        return $this->render('SecurityBundle:Login:index.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
            'registration_form' => $registrationForm->createView()
        ));
    }

    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user, array(
            'action' => $this->generateUrl('user_registration')
        ));

        $validator = $this->get('validator');
        $errors = $validator->validate($user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this
                ->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            return $this->redirectToRoute('dashboard_index');
        }

        return $this->render('TaxigoBundleSecurityBundle:Registration:register.html.twig',
            array(
                'form' => $form->createView(),
                'errors' => $errors
            )
        );
    }
}
