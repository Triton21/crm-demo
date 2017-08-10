<?php

namespace LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Settings;
use AppBundle\Entity\Unregistered;
use AppBundle\Form\ClientType;
use AppBundle\Entity\Client;

class PagesController extends Controller {

    /**
     */
    public function contactAction(Request $request) {

        $contact = [];

        $form = $this->createFormBuilder($contact)
                ->add('name', 'text', array(
                    'label' => 'Name',
                ))
                ->add('phone', 'text', array(
                    'label' => 'Phone',
                ))
                ->add('email', 'email', array(
                    'label' => 'Email',
                ))
                ->add('message', 'textarea', array(
                    'label' => 'Message',
                    'attr' => array(
                        'cols' => '5', 'rows' => '5',
            )))
                ->add('save', 'submit', array('label' => 'Send'))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->captchaverify($request->get('g-recaptcha-response'))) {
                $em = $this->getDoctrine()->getManager();
                $textMessage = $form["message"]->getData();
                $textName = $form["name"]->getData();
                $textPhone = $form["phone"]->getData();
                $textEmail = $form["email"]->getData();

                $messageBody = ' ' . $textMessage . ' From: ' . $textName . ' Phone:' . $textPhone . ' Email: ' . $textEmail;
                $fromname = 'Dentfirst Referral';
                $mySettingArray = $em->getRepository('AppBundle:Settings')
                        ->findAll();
                //var_dump($mySetting);die;
                $mySetting = $mySettingArray[0];
                $smtp = $mySetting->getSmtp();
                $port = $mySetting->getPort();
                $mssl = $mySetting->getEssl();
                $euser = $mySetting->getEusername();
                $epass = $mySetting->getEpassword();
                $auth = $mySetting->getAuth();
                //$fromname = $mySetting->getFromname();
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
                $message = \Swift_Message::newInstance('New message from Dent1st Referral' . $textName)
                        ->setFrom(array($euser => $fromname))
                        ->setTo('petercsatai@gmail.com')
                        ->setBody($messageBody, 'text/html')
                ;
                $mailer->getTransport()->start();
                $mailer->send($message);
                $mailer->getTransport()->stop();

                $this->addFlash(
                        'notice', 'Your message has been sent!'
                );
            }
            return $this->redirectToRoute('login_contact');
        }
        return $this->render('LoginBundle:Default:contactus.html.twig', array(
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

    /**
     */
    public function callbackAction(Request $request) {

        $contact = [];

        $form = $this->createFormBuilder($contact)
                ->add('name', 'text', array(
                    'label' => 'Name',
                ))
                ->add('phone', 'text', array(
                    'label' => 'Phone',
                ))
                ->add('message', 'text', array(
                    'label' => 'Message',
                ))
                ->add('save', 'submit', array('label' => 'Request a call back'))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $searchTerm = $data['search'];
            $result = $this->searchClient($searchTerm);
            $clientlist = false;
            $pager = false;
            return $this->redirectToRoute('');
        }





        return $this->render('LoginBundle:Default:callback.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     */
    public function findusAction() {


        return $this->render('LoginBundle:Default:findus.html.twig', array(
                        // 'form' => $form->createView(),
        ));
    }

    /**
     */
    public function phoneAction() {


        return $this->render('LoginBundle:Default:phone.html.twig', array(
                        // 'form' => $form->createView(),
        ));
    }

    /**
     */
    public function infoAction() {


        return $this->render('LoginBundle:Default:dentist-info.html.twig', array(
                        // 'form' => $form->createView(),
        ));
    }

    /**
     */
    public function aboutAction() {


        return $this->render('LoginBundle:Default:about.html.twig', array(
                        // 'form' => $form->createView(),
        ));
    }

    /**
     */
    public function referralformAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $client = new Client();
        $completed = false;
        $form = $this->createForm(new ClientType(), $client);
        $form->add('practice_name', 'text', array('mapped' => false))
                ->add('contact_name', 'text', array('mapped' => false))
                ->add('practice_phone', 'text', array('mapped' => false))
                ->add('practice_email', 'text', array('mapped' => false))
                ->add('practice_gdc', 'text', array('mapped' => false))
                ->add('practice_address', 'text', array('mapped' => false));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->captchaverify($request->get('g-recaptcha-response'))) {
                $data = $form->getData();
                //var_dump($form->get('contact_name')->getData());die;
                $practice_email = $form->get('practice_email')->getData();

                //check if the practice already registered
                $checkUnregistered = $em->getRepository('AppBundle:Unregistered')
                        ->findOneBy(array('email' => $practice_email));
                if ($checkUnregistered) {
                    $unregistered = $checkUnregistered;
                } else {
                    $unregistered = new Unregistered();
                }

                $unregistered->setCreatedAt(new \DateTime());
                $unregistered->setName($form->get('contact_name')->getData());
                $unregistered->setPhone($form->get('practice_phone')->getData());
                $unregistered->setEmail($practice_email);
                $unregistered->setGdc($form->get('practice_gdc')->getData());
                $unregistered->setAddress($form->get('practice_address')->getData());

                $tokenRaw = bin2hex(openssl_random_pseudo_bytes(32));
                $time = time();
                $token = $tokenRaw . '' . $time;
                $client->setUsername('Unregistered');
                $firstName = $form->get('firstname')->getData();
                $lastName = $form->get('lastname')->getData();

                $client->setName($firstName . ' ' . $lastName);
                $client->setToken($token);
                //$client->setUserId($userId);

                $em->persist($client);
                $em->persist($unregistered);
                $em->flush();
                $completed = true;
                $request->getSession()
                        ->getFlashBag()
                        ->add('success', 'Thanks for the patient referral. Our patient care team will contact you shortly.');
            }
            return $this->render('LoginBundle:Default:referralform.html.twig', array(
                        'form' => $form->createView(), 'completed' => $completed,
            ));
        }

        return $this->render('LoginBundle:Default:referralform.html.twig', array(
                    'form' => $form->createView(), 'completed' => $completed,
        ));
    }
}
