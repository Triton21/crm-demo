<?php

// src/AppBundle/Controller/SecurityController.php

namespace LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use LoginBundle\Entity\User;
use LoginBundle\Entity\Demo;
use LoginBundle\Entity\Contact;
use LoginBundle\Form\UserType;
use LoginBundle\Form\ContactType;
use LoginBundle\Form\DemoType;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\JsonResponse;

class SecurityController extends Controller {

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request) {

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        //registration form
        $demo = new Demo();
        
        $form = $this->createForm(new DemoType(), $demo, array(
            'action' => $this->generateUrl('send_register_ajax'),
            'method' => 'POST',));
        
        $contact = new Contact();
        
        $contactForm = $this->createForm(new ContactType(), $contact, array(
            'action' => $this->generateUrl('send_contact_ajax'),
            'method' => 'POST',));
        
        return $this->render(
                    'Security/login.html.twig', array(
                    'error' => $error,
                    'form' => $form->createView(),
                    'contactForm' => $contactForm->createView(),
                        )
        );
    }
    
    /*
     * Send register message to the default email address
     */
    public function sendRegisterAjaxAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $demo = new Demo();
        $form = $this->createForm(new DemoType(), $demo, array(
            'action' => $this->generateUrl('send_register_ajax'),
            'method' => 'POST',));
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $demo->setCreatedAt(new \DateTime());
            $demo->setSent(0);
            $em->persist($demo);
            $em->flush();
            
            $consoleMessage = 'success';
            $response = new JsonResponse($consoleMessage);
            return $response;
        }
        $html = 'ERROR';
        $response = new JsonResponse($html);
        return $response;
    }

    /*
     * Send contact us message to the default email address
     */
    public function sendContactAjaxAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $contact = new Contact();
        $contactForm = $this->createForm(new ContactType(), $contact, array(
            'action' => $this->generateUrl('send_contact_ajax'),
            'method' => 'POST',));
        $contactForm->handleRequest($request);
        
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->all();
            $message = $data['loginbundle_contact']['message'];
            $name = $data['loginbundle_contact']['name'];
            $phone = $data['loginbundle_contact']['phone'];
            $email = $data['loginbundle_contact']['email'];
            
            $contact = new Contact();
            $contact->setCreatedAt(new \DateTime());
            $contact->setMessage($message);
            $contact->setEmail($email);
            $contact->setName($name);
            $contact->setSent(0);
            $contact->setPhone($phone);
            
            $em->persist($contact);
            $em->flush();
            
            $consoleMessage = 'success';
            $response = new JsonResponse($consoleMessage);
            return $response;
        }
        $html = 'ERROR';
        $response = new JsonResponse($html);
        return $response;
    }

    /**
     * @Route("/login", name="login")
     */
    public function customerLoginAction(Request $request) {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();


        return $this->render(
                        'Security/customerlogin.html.twig', array(
                    // last username entered by the user
                    'error' => $error,
                        )
        );
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction() {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction() {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }

}
