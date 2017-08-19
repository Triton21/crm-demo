<?php

namespace LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use LoginBundle\Entity\User;
use LoginBundle\Entity\Customer;
use LoginBundle\Form\UserType;
use LoginBundle\Form\CustomerType;
use LoginBundle\Form\Model\ChangePassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends Controller {

    /**
     * Register new user
     */
    public function registerAction(Request $request) {
        //login
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        //register
        $entity = new User();
        $form = $this->createForm(new UserType, $entity);

        $form->remove('isAdmin');
        $form->remove('isActive');
        $form->remove('isSuperAdmin');
        $form->add('submit', 'submit', array(
            'label' => 'Register',
            'attr' => array('class' => 'btn btn-lg btn-primary')
        ));

        $form->handleRequest($request);

        if ('POST' === $request->getMethod()) {
            if ($form->isValid()) {
                //$verify = $this->captchaverify($request->get('g-recaptcha-response'));
                //turn on captcha verify
                if ($this->captchaverify($request->get('g-recaptcha-response'))) {
                    $em = $this->getDoctrine()->getManager();
                    $this->get('app_bundle.user_manager')
                            ->setUserPassword($entity, $entity->getPassword());
                    $entity->setRoles(array('ROLE_USER'));
                    $entity->setIsAdmin(false);
                    $entity->setIsActive(false);
                    $em->persist($entity);
                    $em->flush();
                    $newId = $entity->getId();

                    //!!!! here is the redirection after registration
                    $request->getSession()
                            ->getFlashBag()
                            ->add('success', 'Verification email was sent to your email address');
                }
                return $this->redirect($this->generateUrl('login'));
            }
            return $this->render('LoginBundle:User:register.html.twig', array(
                        'error' => $error,
                        'entity' => $entity,
                        'form' => $form->createView(),
            ));
        }

        return $this->render('LoginBundle:User:register.html.twig', array(
                    'error' => $error,
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    function captchaverify($captcha) {
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        $privatekey = "6LfGlhEUAAAAAMMUVs4uixxUyuGrkcNekDzzeZE1";
        $data = array(
            'secret' => $privatekey,
            'response' => $captcha,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );

        $curlConfig = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data
        );

        $ch = curl_init();
        curl_setopt_array($ch, $curlConfig);
        $response = curl_exec($ch);
        curl_close($ch);
        $jsonResponse = json_decode($response);
        return $jsonResponse->success;
    }

    function sendVerificationAction($newId) {
        $em = $this->getDoctrine()->getManager();
        $newUser = $em->getRepository('LoginBundle:User')
                ->find($newId);
        $newEmail = $newUser->getEmail();
        $newFirstname = $newUser->getFirstname();
        $newUsername = $newUser->getUsername();

        $company = $em->getRepository('AppBundle:Company')
                ->findOneBy(array('active' => 1));
        //$fromEmail = $company->getEmail();
        $tokenRaw = bin2hex(openssl_random_pseudo_bytes(32));
        $time = time();
        $token = $tokenRaw . '' . $time;
        //$tokenLink = 'referral.dent1st.co.uk/practice/' . $token;
        $tokenLink = 'http://localhost/referral/web/app_dev.php/practice/' . $token;

        $newUser->setToken($token);
        $em->persist($newUser);
        $em->flush();

        //var_dump($token);die;
        $body = $this->renderView('LoginBundle:User:emailbody.html.twig', array(
            'newUsername' => $newUsername, 'newFirstname' => $newFirstname, 'tokenLink' => $tokenLink, 'newEmail' => $newEmail,));
        $subject = 'Dear ' . $newFirstname . '! Welcome to Dent1st Referral. Please activate your account!';
        $mySetting = $em->getRepository('AppBundle:Settings')
                ->findOneBy(array('defaultemail' => 1));
        $smtp = $mySetting->getSmtp();
        $port = $mySetting->getPort();
        $mssl = $mySetting->getEssl();
        $euser = $mySetting->getEusername();
        $epass = $mySetting->getEpassword();
        $auth = $mySetting->getAuth();
        $fromname = $mySetting->getFromname();

        if ($auth) {
            $transport = \Swift_SmtpTransport::newInstance($smtp, $port)
                    ->setUsername($euser)
                    ->setPassword($epass)
                    ->setAuthMode('PLAIN')
            ;
        } else {
            $transport = \Swift_SmtpTransport::newInstance($smtp, $port, $mssl)
                    ->setUsername($euser)
                    ->setPassword($epass)
                    ->setAuthMode('PLAIN')
            ;
        }

        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance($subject)
                ->setFrom(array($euser => $fromname))
                ->setTo(array($newEmail => $newFirstname))
                ->setBody($body, 'text/html')
        ;
        $mailer->getTransport()->start();
        $mailer->send($message);
        $mailer->getTransport()->stop();

        return $this->redirect($this->generateUrl('login'));
    }

    function activationAction(Request $request, $token) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('LoginBundle:User')
                ->findOneBy(array('token' => $token));
        if ($user) {
            $isActive = $user->getIsActive();
            if ($isActive != false) {
                $request->getSession()
                        ->getFlashBag()
                        ->add('success', 'Your account already active. Please login with your username and password!');
                return $this->redirect($this->generateUrl('login'));
            } else {
                $user->setIsActive(1);
                $em->persist($user);
                $em->flush();
                $name = $user->getFirstname();
                $request->getSession()
                        ->getFlashBag()
                        ->add('success', 'Your account has been activated. You can log in now with your username and password!');
                return $this->redirect($this->generateUrl('login'));
            }
        }
        return $this->redirect($this->generateUrl('login'));
    }

    function forgotAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $email = [];

        $form = $this->createFormBuilder($email)
                ->add('email', 'email', array(
                    'label' => 'Email',
                    'required' => true,
                    'attr' => array(
                        'placeholder' => 'Email address'
                    )
                ))
                ->add('submit', 'submit', array(
                    'label' => 'Submit',
                    'attr' => array('class' => 'btn btn-lg btn-primary')
                ))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $email = strip_tags(strtolower(trim($data['email'])));
            $user = $em->getRepository('LoginBundle:User')
                    ->findOneBy(array('email' => $email));
            if ($user) {
                $this->reset($user);
                $request->getSession()
                        ->getFlashBag()
                        ->add('success', 'Please check your emails and follow the instructions to reset your password!');
                return $this->redirect($this->generateUrl('login'));
            } else {
                $request->getSession()
                        ->getFlashBag()
                        ->add('success', 'Email address does not exist in our system!');
                return $this->redirect($this->generateUrl('login'));
            }

            return $this->redirectToRoute('task_success');
        }

        return $this->render('LoginBundle:User:forgot.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    function resetPasswordAction(Request $request, $token) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('LoginBundle:User')
                ->findOneBy(array('reset' => $token));

        if (!$user) {
            throw $this->createNotFoundException('Undable to find User entity!');
        }

        $form = $this->createForm(new UserType, $user);
        $form->remove('gdc');
        $form->remove('phone');
        $form->remove('isAdmin');
        $form->remove('practice');
        $form->remove('address');
        $form->remove('username');
        $form->remove('firstname');
        $form->remove('lastname');
        $form->remove('email');
        $form->remove('isSuperAdmin');

        $form->add('submit', 'submit', array(
            'label' => 'Save New Password',
            'attr' => array('class' => 'btn btn-primary btn-lg'),
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app_bundle.user_manager')->setUserpassword($user, $user->getPassword());
            $em->flush();

            $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Your password has been changed. You can login now with the new password.');
            return $this->redirect($this->generateUrl('login'));
        }

        return $this->render('LoginBundle:User:reset-password.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    function changePasswordAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $login = $this->getUser();
        $name = $login->getUsername();
        $id = $login->getId();

        $user = $em->getRepository('LoginBundle:User')
                ->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Undable to find User entity!');
        }


        /*
          $oldpassword = $user->getPassword();

          $form = $this->createForm(new UserType, $user);
          $form->remove('gdc');
          $form->remove('phone');
          $form->remove('isAdmin');
          $form->remove('practice');
          $form->remove('address');
          $form->remove('username');
          $form->remove('firstname');
          $form->remove('lastname');
          $form->remove('email');

          $form->add('oldPlainPassword', 'password', array(
          'mapped' => false,
          'required' => true,
          'label' => 'Current Password',
          ));
          $form->add('submit', 'submit', array(
          'label' => 'Save New Password',
          'attr' => array('class' => 'btn btn-primary btn-lg'),
          ));
         * 
         */


        $changePassword = new ChangePassword();

        $form = $this->createFormBuilder($changePassword)
                ->add('oldpassword', 'password', array(
                    'label' => 'Old password',
                    'required' => true,
                ))
                ->add('password', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'The password fields must match.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => true,
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Repeat Password'),
                ))
                ->add('submit', 'submit', array(
                    'label' => 'Save New Password',
                    'attr' => array('class' => 'btn btn-primary btn-lg'),
                ))
                ->getForm();



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $newPassword = $form["password"]->getData();
            //var_dump($newPassword);die;
            
            
            $this->get('app_bundle.user_manager')->setUserpassword($user, $newPassword);
            $em->flush();

            $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Your password has been changed');
            return $this->redirect($this->generateUrl('app_account'));
        }

        return $this->render('LoginBundle:User:change-password.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    function reset($user) {
        $em = $this->getDoctrine()->getManager();

        $newEmail = $user->getEmail();
        $newFirstname = $user->getFirstname();
        $newUsername = $user->getUsername();


        $tokenRaw = bin2hex(openssl_random_pseudo_bytes(32));
        $time = time();
        $token = $tokenRaw . '' . $time;
        //$tokenLink = 'referral.dent1st.co.uk/reset-password/' . $token;
        $tokenLink = 'http://localhost/referral/web/app_dev.php/reset-password/' . $token;
        $user->setReset($token);
        $em->persist($user);
        $em->flush();

        $body = $this->renderView('LoginBundle:User:emailbodyreset.html.twig', array(
            'newUsername' => $newUsername, 'newFirstname' => $newFirstname, 'newEmail' => $newEmail, 'tokenLink' => $tokenLink,));
        $subject = 'Dear ' . $newFirstname . '! Reset your password for Dent1st Referral Account!';
        $mySetting = $em->getRepository('AppBundle:Settings')
                ->findOneBy(array('defaultemail' => 1));
        $smtp = $mySetting->getSmtp();
        $port = $mySetting->getPort();
        $mssl = $mySetting->getEssl();
        $euser = $mySetting->getEusername();
        $epass = $mySetting->getEpassword();
        $auth = $mySetting->getAuth();
        $fromname = $mySetting->getFromname();

        if ($auth) {
            $transport = \Swift_SmtpTransport::newInstance($smtp, $port)
                    ->setUsername($euser)
                    ->setPassword($epass)
                    ->setAuthMode('PLAIN')
            ;
        } else {
            $transport = \Swift_SmtpTransport::newInstance($smtp, $port, $mssl)
                    ->setUsername($euser)
                    ->setPassword($epass)
                    ->setAuthMode('PLAIN')
            ;
        }

        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance($subject)
                ->setFrom(array($euser => $fromname))
                ->setTo(array($newEmail => $newFirstname))
                ->setBody($body, 'text/html')
        ;
        $mailer->getTransport()->start();
        $mailer->send($message);
        $mailer->getTransport()->stop();
        return true;
    }

}
