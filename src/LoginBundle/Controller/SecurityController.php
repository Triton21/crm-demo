<?php

// src/AppBundle/Controller/SecurityController.php

namespace LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use LoginBundle\Entity\User;
use AppBundle\Entity\Demo;
use LoginBundle\Form\UserType;
use Symfony\Component\Yaml\Yaml;

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

        $form = $this->createFormBuilder($demo)
                ->add('name', 'text', array(
                    'attr' => array('class' => 'input-md form-control', 'placeholder' => 'Name')
                ))
                ->add('email', 'email', array(
                    'attr' => array('class' => 'input-md form-control', 'placeholder' => 'Email')
                ))
                ->add('phone', 'text', array(
                    'attr' => array('class' => 'input-md form-control', 'placeholder' => 'Phone')
                ))
                ->add('save', 'submit', array('label' => 'SUBMIT', 'attr' => array('class' => 'btn btn-md btn-success')))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $demo->setCreatedAt(new \DateTime());
            $em->persist($demo);
            $em->flush();

            $parameterPath = $this->container->getParameter('kernel.root_dir') . '/config/emailParameters.yml';
            $value = Yaml::parse(file_get_contents($parameterPath));
            $euser = $value['parameters']['sender-name'];
            $email = $value['parameters']['email'];
            $epass = $value['parameters']['password'];
            $messagesend = 'New demo request received. Name: ' . $demo->getName() . ' Email:' . $demo->getEmail() . ' Phone:' . $demo->getPhone();
            $subject = 'New demo request received. Name: ' . $demo->getName();

            $transporter = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                    ->setUsername($email)
                    ->setPassword($epass);

            $mailer = \Swift_Mailer::newInstance($transporter);

            $message = \Swift_Message::newInstance($subject)
                    ->setFrom(array($email => $euser))
                    ->setTo(array($email => $euser))
                    ->setBody($messagesend, 'text/html')
            ;

            $mailer->getTransport()->start();
            $mailer->send($message);
            $mailer->getTransport()->stop();

            return $this->redirectToRoute('login');
        }

        return $this->render(
                    'Security/login.html.twig', array(
                    'error' => $error,
                    'form' => $form->createView(),
                        )
        );
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
