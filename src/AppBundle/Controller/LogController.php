<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Lead;
use AppBundle\Entity\Callhistory;
use AppBundle\Entity\Alarm;
use AppBundle\Entity\Message;
use AppBundle\Entity\Userlog;
use AppBundle\Entity\Telephonelog;
use AppBundle\Entity\Loghistory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class LogController extends Controller {

    public function userLogRegisterAction($register) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $userlog = new Userlog();
        $userlog->setUsername($name);
        $userlog->setRegister($register);
        $userlog->setCreatedAt(new \DateTime());
        $em->persist($userlog);
        $em->flush();
        return true;
    }

    //Telephone log manager controller
    public function logajaxalarmsetupAction(Request $request, $id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $message = 'Something is wrong';
        if ($request->getMethod() == "POST") {
            $aDate = $request->get("reminderdate");
            $aTime = $request->get("remindertime");
            $note = $request->get("remindernote");
            $cName = $request->get("cname");
            $alarm = new Alarm();
            $alarm->setCreatedAt(new \DateTime());
            $alarm->setUser($name);
            $alarm->setCName($cName);
            $alarm->setNote($note);
            $alarm->setLogid($id);
            $merge = $aDate . ' ' . $aTime;
            $myTime = new \DateTime($merge);
            $alarm->setAlarmAt($myTime);
            $em->persist($alarm);

            $loghistory = new Loghistory();
            $loghistory->setCalldate(new \DateTime());
            $loghistory->setNote($note . '  (Alarm time: ' . $merge . ')');
            $loghistory->setAssign($name);
            $loghistory->setStatus('alarm');
            $loghistory->setLogid($id);
            $em->persist($loghistory);
            $em->flush();

            //User register
            $register = $cName . ' log alarm has been set';
            $this->userLogRegisterAction($register);


            $message = 'Coool :)';
        }

        $response = new Response(json_encode($message));
        return $response;
    }

    public function logajaxgetalarmAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thisalarm = $em->getRepository('AppBundle:Alarm')
                ->findOneBy(array('logid' => $id,));
        if ($thisalarm) {
            $html = $this->renderView('AppBundle:Log:ajaxstoredAlarm.html.twig', array('thisalarm' => $thisalarm, 'id' => $id,));
        } else {
            $html = 'no alarm';
        }
        $response = new Response(json_encode($html));
        return $response;
    }

    public function logajaxalarmoffAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thisalarm = $em->getRepository('AppBundle:Alarm')
                ->findOneBy(array('logid' => $id,));
        if ($thisalarm) {
            $em->remove($thisalarm);
            $em->flush();
            $html = 'delete successfull';
        } else {
            $html = 'no alarm';
        }
        $response = new Response(json_encode($html));
        return $response;
    }

    public function logvisitturnoffalarmAction($id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $thisalarm = $em->getRepository('AppBundle:Alarm')
                ->findOneBy(array('id' => $id,));
        if ($thisalarm) {
            $logid = $thisalarm->getLogid();
            $em->remove($thisalarm);
            $em->flush();
            $html = 'delete successfull';
        } else {
            $html = 'no alarm';
        }
        return $this->redirect($this->generateUrl('log_progress', array('id' => $logid)));
    }

    public function logalarmlistAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $countlog = $this->countlog();
        $em = $this->getDoctrine()->getManager();
        $thisalarm = $em->getRepository('AppBundle:Alarm')
                ->findAll();

        return $this->render('AppBundle:Log:logalarmlist.html.twig', array(
                    'name' => $name, 'thisalarm' => $thisalarm, 'countlog' => $countlog,
        ));
    }

    public function autocompleteAction() {
        $em = $this->getDoctrine()->getManager();
        $autocomplete = $em->getRepository('AppBundle:Lead')
                ->autocomplete();
        $arraylength = count($autocomplete);
        for ($i = 0; $i < $arraylength; $i++) {
            $autocomplete[$i]['value'] = $autocomplete[$i]['customerName'];
        }
        $response = new Response(json_encode($autocomplete));
        return $response;
    }

    public function ajaxreadmessageAction($id) {
        $em = $this->getDoctrine()->getManager();
        $login = $this->getUser();
        $name = $login->getUsername();
        $message = $em->getRepository('AppBundle:Message')
                ->find($id);

        $html = $this->renderView('AppBundle:Log:ajaxreadmessage.html.twig', array('message' => $message));

        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxchangereadAction($id) {
        $em = $this->getDoctrine()->getManager();
        $login = $this->getUser();
        $name = $login->getUsername();
        $html = "X";
        $message = $em->getRepository('AppBundle:Message')
                ->find($id);
        if ($message) {
            $message->setUnread(1);
            $em->flush();
            $html = "success";
        }
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxsentmessageAction($page) {
        $em = $this->getDoctrine()->getManager();
        $login = $this->getUser();
        $name = $login->getUsername();
        //$offset = 0;
        $itemperpage = 10;
        $countMessage = $em->getRepository('AppBundle:Message')
                ->countAllByName($name);
        $pages = ceil($countMessage / $itemperpage);
        $offset = $page * $itemperpage - $itemperpage;
        $sent = $em->getRepository('AppBundle:Message')
                ->findAllByName($offset, $itemperpage, $name);
        $pagesarray = array();
        for ($i = 1; $i <= $pages; $i++) {
            $pagesarray[] = $i;
        }
        if ($page <= '5') {
            $pagesarray = array_slice($pagesarray, 0, 10);
        }
        if ($page > '5') {
            $pagesarray = array_slice($pagesarray, $page - 5, 10);
        }

        $maxpage = end($pagesarray);
        $html = $this->renderView('AppBundle:Log:ajaxsentmessage.html.twig', array(
            'maxpage' => $maxpage, 'pagesarray' => $pagesarray, 'page' => $page, 'sent' => $sent));

        $response = new Response(json_encode($html));
        return $response;
    }

    public function logsentmessageAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $countlog = $this->countlog();

        return $this->render('AppBundle:Log:logsentmessage.html.twig', array(
                    'countlog' => $countlog, 'name' => $name,));
    }

    public function logcreatemessageAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $countlog = $this->countlog();

        $findusers = $em->getRepository('LoginBundle:User')->findAll();
        $allusersarray = array(0 => ' ');
        foreach ($findusers as $us) {
            $allusersarray[$us->getUsername()] = $us->getUsername();
        }
        $to_remove = array('admin');
        $usersarray = array_diff($allusersarray, $to_remove);
        $usersarray['Everyone'] = 'Everyone';

        $crmMessage = new Message();

        $form = $this->createFormBuilder($crmMessage)
                ->add('customerName', 'text', array('required' => false,
                    'label' => 'Patient', 'attr' => array('class' => 'input-sm', "autocomplete" => "new-password")
                ))
                ->add('leadid', 'text', array('required' => false,
                    'attr' => array('class' => 'input-sm', 'style' => 'display:none;')
                ))
                ->add('assign', 'choice', array(
                    'label' => 'To',
                    'choices' => $usersarray, 'attr' => array('class' => 'input-sm')
                ))
                ->add('deadline', 'date', [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control input-inline datepicker',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'placeholder' => 'dd-mm-yyyy',
                        'class' => 'input-sm'
                    ]
                ])
                ->add('subject', 'text', array(
                    'label' => 'Subject', 'attr' => array('class' => 'input-sm')
                ))
                ->add('body', 'textarea', array(
                    'label' => 'Message', 'attr' => array('class' => 'form-control', 'cols' => '6', 'rows' => '8')
                ))
                ->add('sendmail', 'checkbox', array(
                    'label' => 'Send copy by email', 'required' => false, 'attr' => array('required' => false,)
                ))
                ->add('priority', 'checkbox', array(
                    'label' => 'Important', 'required' => false, 'attr' => array('required' => false,)
                ))
                ->add('save', 'submit', array(
                    'label' => 'Send', 'attr' => array('class' => 'btn-success btn-md')))
                ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $myMessage = $form->getData();
            $leadid = $myMessage->getLeadid();
            $sendto = $myMessage->getAssign();
            if ($sendto === 'Everyone') {
                $to_remove = array(' ', 'Everyone');
                $sendtoarray = array_diff($usersarray, $to_remove);
                //User register
                $register = 'Internal message sent to everyone';
                $this->userLogRegisterAction($register);

                foreach ($sendtoarray as $sendto) {
                    $newmessage = new Message();
                    $newmessage = $crmMessage;
                    $newmessage->setUsername($name);
                    $newmessage->setAssign($sendto);
                    $newmessage->setCreatedAt(new \DateTime());
                    $em->persist($newmessage);
                    $em->flush();
                    $em->clear();

                    $sendcopy = $myMessage->getSendmail();
                    if ($sendcopy) {
                        $findrecepient = $em->getRepository('LoginBundle:User')
                                ->findOneBy(array('username' => $sendto));
                        $realemail = $findrecepient->getEmail();

                        $thissettings = $em->getRepository('AppBundle:Settings')
                                ->findOneBy(array('eusername' => 'info@dent1st.co.uk'));
                        $subject = $myMessage->getSubject();
                        $messagebody = $myMessage->getBody();
                        if ($leadid) {
                            $thispatient = $em->getRepository('AppBundle:Lead')
                                    ->find($leadid);
                            $patientName = $thispatient->getCustomerName();
                            $emailsubject = '-- New message from CRM Dent1st --  Patient name: ' . $patientName . '   ' . $subject;
                            $emailbody = '--  Patient name: ' . $patientName . '   ' . $messagebody;
                        } else {
                            $emailsubject = '-- New message from CRM Dent1st --  ' . $subject;
                            $emailbody = $messagebody;
                        }

                        $smtp = $thissettings->getSmtp();
                        $port = $thissettings->getPort();
                        $mssl = $thissettings->getEssl();
                        $euser = $thissettings->getEusername();
                        $fromname = $thissettings->getFromname();
                        $epass = $thissettings->getEpassword();
                        $auth = $thissettings->getAuth();
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
                        $message = \Swift_Message::newInstance($emailsubject)
                                ->setFrom(array($euser => $fromname))
                                ->setTo(array($realemail => $sendto))
                                ->setBody($emailbody, 'text/html')
                        ;
                        try {
                            $mailer->getTransport()->start();
                            $mailer->send($message);
                            $mailer->getTransport()->stop();
                        } catch (Swift_TransportException $e) {
                            $mailer->getTransport()->stop();
                            $error = true;
                            return true;
                        } catch (Exception $e) {
                            $mailer->getTransport()->stop();
                            $error = true;
                            return true;
                        }
                    }
                }
            } else {
                //User register
                $register = 'Internal message sent to: ' . $sendto;
                $this->userLogRegisterAction($register);

                $crmMessage->setUsername($name);
                $crmMessage->setCreatedAt(new \DateTime());
                $em->persist($crmMessage);
                $em->flush();
                $sendcopy = $myMessage->getSendmail();
                if ($sendcopy) {
                    $findrecepient = $em->getRepository('LoginBundle:User')
                            ->findOneBy(array('username' => $sendto));
                    $realemail = $findrecepient->getEmail();

                    $thissettings = $em->getRepository('AppBundle:Settings')
                            ->findOneBy(array('eusername' => 'info@dent1st.co.uk'));
                    $subject = $myMessage->getSubject();
                    $messagebody = $myMessage->getBody();
                    if ($leadid) {
                        $thispatient = $em->getRepository('AppBundle:Lead')
                                ->find($leadid);
                        $patientName = $thispatient->getCustomerName();
                        $emailsubject = '-- New message from CRM Dent1st --  Patient name: ' . $patientName . '   ' . $subject;
                        $emailbody = '--  Patient name: ' . $patientName . '   ' . $messagebody;
                    } else {
                        $emailsubject = '-- New message from CRM Dent1st --  ' . $subject;
                        $emailbody = $messagebody;
                    }
                    $smtp = $thissettings->getSmtp();
                    $port = $thissettings->getPort();
                    $mssl = $thissettings->getEssl();
                    $euser = $thissettings->getEusername();
                    $fromname = $thissettings->getFromname();
                    $epass = $thissettings->getEpassword();
                    $auth = $thissettings->getAuth();
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
                    $message = \Swift_Message::newInstance($emailsubject)
                            ->setFrom(array($euser => $fromname))
                            ->setTo(array($realemail => $sendto))
                            ->setBody($emailbody, 'text/html')
                    ;
                    try {
                        $mailer->getTransport()->start();
                        $mailer->send($message);
                        $mailer->getTransport()->stop();
                    } catch (Swift_TransportException $e) {
                        $mailer->getTransport()->stop();
                        $error = true;
                        return true;
                    } catch (Exception $e) {
                        $mailer->getTransport()->stop();
                        $error = true;
                        return true;
                    }
                }
            }
            return $this->redirectToRoute('log_message');
        }
        return $this->render('AppBundle:Log:logcreatemessage.html.twig', array(
                    'form' => $form->createView(), 'countlog' => $countlog, 'name' => $name,));
    }

    public function logcontactadminAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $countlog = $this->countlog();


        $admin = 'Peter';

        $crmMessage = new Message();

        $form = $this->createFormBuilder($crmMessage)
                ->add('customerName', 'text', array('required' => false,
                    'label' => 'Patient', 'attr' => array('class' => 'input-sm', 'placeholder' => 'Optional')
                ))
                ->add('leadid', 'text', array('required' => false,
                    'attr' => array('class' => 'input-sm', 'style' => 'display:none;')
                ))
                ->add('assign', 'text', array(
                    'read_only' => true,
                    'label' => 'To',
                    'data' => $admin, 'attr' => array('class' => 'input-sm')
                ))
                ->add('deadline', 'date', [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control input-inline datepicker',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'placeholder' => 'dd-mm-yyyy',
                        'class' => 'input-sm'
                    ]
                ])
                ->add('subject', 'text', array(
                    'label' => 'Subject', 'attr' => array('class' => 'input-sm')
                ))
                ->add('body', 'textarea', array(
                    'label' => 'Message', 'attr' => array('class' => 'form-control', 'cols' => '6', 'rows' => '8')
                ))
                ->add('sendmail', 'checkbox', array(
                    'label' => 'Send copy by email', 'required' => false, 'attr' => array('required' => false,)
                ))
                ->add('priority', 'checkbox', array(
                    'label' => 'Important', 'required' => false, 'attr' => array('required' => false,)
                ))
                ->add('save', 'submit', array(
                    'label' => 'Send', 'attr' => array('class' => 'btn-success btn-md')))
                ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $myMessage = $form->getData();
            $leadid = $myMessage->getLeadid();
            $sendto = $myMessage->getAssign();

            $crmMessage->setUsername($name);
            $crmMessage->setCreatedAt(new \DateTime());
            $em->persist($crmMessage);
            $em->flush();
            $sendcopy = $myMessage->getSendmail();
            if ($sendcopy) {
                $findrecepient = $em->getRepository('LoginBundle:User')
                        ->findOneBy(array('username' => $sendto));
                $realemail = $findrecepient->getEmail();

                $thissettings = $em->getRepository('AppBundle:Settings')
                        ->findOneBy(array('eusername' => 'info@dent1st.co.uk'));
                $subject = $myMessage->getSubject();
                $messagebody = $myMessage->getBody();
                if ($leadid) {
                    $thispatient = $em->getRepository('AppBundle:Lead')
                            ->find($leadid);
                    $patientName = $thispatient->getCustomerName();
                    $emailsubject = '-- New message from CRM Dent1st --  Patient name: ' . $patientName . '   ' . $subject;
                    $emailbody = '--  Patient name: ' . $patientName . '   ' . $messagebody;
                } else {
                    $emailsubject = '-- New message from CRM Dent1st --  ' . $subject;
                    $emailbody = $messagebody;
                }
                $smtp = $thissettings->getSmtp();
                $port = $thissettings->getPort();
                $mssl = $thissettings->getEssl();
                $euser = $thissettings->getEusername();
                $fromname = $thissettings->getFromname();
                $epass = $thissettings->getEpassword();
                $auth = $thissettings->getAuth();
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
                $message = \Swift_Message::newInstance($emailsubject)
                        ->setFrom(array($euser => $fromname))
                        ->setTo(array($realemail => $sendto))
                        ->setBody($emailbody, 'text/html')
                ;
                try {
                    $mailer->getTransport()->start();
                    $mailer->send($message);
                    $mailer->getTransport()->stop();
                } catch (Swift_TransportException $e) {
                    $mailer->getTransport()->stop();
                    $error = true;
                    return true;
                } catch (Exception $e) {
                    $mailer->getTransport()->stop();
                    $error = true;
                    return true;
                }
            }
            return $this->redirectToRoute('log_message');
        }
        return $this->render('AppBundle:Log:logcreatemessage.html.twig', array(
                    'form' => $form->createView(), 'countlog' => $countlog, 'name' => $name,));
    }

    public function logmessageAction(Request $request, $page) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $countlog = $this->countlog();

        //User register
        $this->userLogRegisterAction('Internal message system opened');
        return $this->render('AppBundle:Log:logmessage.html.twig', array(
                    'page' => $page, 'countlog' => $countlog, 'name' => $name,));
    }

    public function ajaxreplyAction(Request $request, $id, $page) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $html = "X";

        $thismessage = $em->getRepository('AppBundle:Message')
                ->find($id);
        $fromname = $thismessage->getUsername();
        $leadid = $thismessage->getLeadid();
        $logid = $thismessage->getLogid();
        $deadline = $thismessage->getDeadline();
        $customerName = $thismessage->getCustomerName();

        $request = $this->getRequest();
        if ($request->getMethod() == "POST") {
            $sendmail = $request->get("sendmail");
            $subject = $request->get("subject");
            $body = $request->get("body");
            $sendcopy = $request->get("sendmail");

            $myMessage = new Message();
            $myMessage->setUsername($name);
            $myMessage->setBody($body);
            $myMessage->setSubject($subject);
            $myMessage->setAssign($fromname);

            //User register
            $register = ' Internal reply sent to: ' . $fromname;
            $this->userLogRegisterAction($register);


            $myMessage->setCreatedAt(new \DateTime());
            if ($leadid) {
                $myMessage->setLeadid($leadid);
            }
            if ($logid) {
                $myMessage->setLogid($logid);
            }
            if ($deadline) {
                $myMessage->setDeadline($deadline);
            }
            if ($customerName) {
                $myMessage->setCustomerName($customerName);
            }
            if ($sendmail) {
                $myMessage->setSendmail($sendmail);
            }
            $em->persist($myMessage);
            $em->flush();

            if ($sendcopy) {
                $findrecepient = $em->getRepository('LoginBundle:User')
                        ->findOneBy(array('username' => $fromname));
                $realemail = $findrecepient->getEmail();

                $thissettings = $em->getRepository('AppBundle:Settings')
                        ->findOneBy(array('eusername' => 'info@dent1st.co.uk'));
                if ($leadid) {
                    $thispatient = $em->getRepository('AppBundle:Lead')
                            ->find($leadid);
                    $patientName = $thispatient->getCustomerName();
                    $emailsubject = '-- New message from CRM Dent1st --  Patient name: ' . $patientName . '   ' . $subject;
                    $emailbody = '--  Patient name: ' . $patientName . '   ' . $body;
                } elseif ($logid) {
                    $emailsubject = '-- New message from CRM Dent1st --  Log ID:' . $logid . '   ' . $subject;
                    $emailbody = '--  Log ID: ' . $logid . '   ' . $body;
                } else {
                    $emailsubject = '-- New message from CRM Dent1st --  ' . $subject;
                    $emailbody = $body;
                }

                $smtp = $thissettings->getSmtp();
                $port = $thissettings->getPort();
                $mssl = $thissettings->getEssl();
                $euser = $thissettings->getEusername();
                $fromEmailname = $thissettings->getFromname();
                $epass = $thissettings->getEpassword();
                $auth = $thissettings->getAuth();
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
                $message = \Swift_Message::newInstance($emailsubject)
                        ->setFrom(array($euser => $fromEmailname))
                        ->setTo(array($realemail => $fromname))
                        ->setBody($emailbody, 'text/html')
                ;
                try {
                    $mailer->getTransport()->start();
                    $mailer->send($message);
                    $mailer->getTransport()->stop();
                } catch (Swift_TransportException $e) {
                    $mailer->getTransport()->stop();
                    $error = true;
                    return true;
                } catch (Exception $e) {
                    $mailer->getTransport()->stop();
                    $error = true;
                    return true;
                }
            }
            return $this->redirectToRoute('log_message', array('page' => $page));
        }
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxreplyformAction($id, $page) {
        $em = $this->getDoctrine()->getManager();
        $thismessage = $em->getRepository('AppBundle:Message')
                ->find($id);
        $subject = $thismessage->getSubject();
        $html = $this->renderView('AppBundle:Default:ajaxreplyform.html.twig', array(
            'subject' => $subject, 'page' => $page, 'id' => $id,));

        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxLogcommunicationAction($customerName) {
        $em = $this->getDoctrine()->getManager();
        $logcomm = $em->getRepository('AppBundle:Telephonelog')
                ->findcommunication($customerName);

        $html = $this->renderView('AppBundle:Log:ajaxLogcommunication.html.twig', array(
            'logcomm' => $logcomm,));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxmessageAction($page) {
        $em = $this->getDoctrine()->getManager();
        $login = $this->getUser();
        $name = $login->getUsername();
        $itemperpage = 10;
        $countMessage = $em->getRepository('AppBundle:Message')
                ->countAllByAssign($name);
        $pages = ceil($countMessage / $itemperpage);
        $offset = $page * $itemperpage - $itemperpage;
        $incoming = $em->getRepository('AppBundle:Message')
                ->findAllByAssign($offset, $itemperpage, $name);
        $pagesarray = array();
        for ($i = 1; $i <= $pages; $i++) {
            $pagesarray[] = $i;
        }
        if ($page <= '5') {
            $pagesarray = array_slice($pagesarray, 0, 10);
        }
        if ($page > '5') {
            $pagesarray = array_slice($pagesarray, $page - 5, 10);
        }

        $maxpage = end($pagesarray);

        $html = $this->renderView('AppBundle:Log:ajaxmessage.html.twig', array(
            'maxpage' => $maxpage, 'pagesarray' => $pagesarray, 'page' => $page, 'incoming' => $incoming));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function logtodolistAction(Request $request, $page, $namefilter) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $this->userLogRegisterAction('Log todo list opened');

        $countlog = $this->countlog();
        $loglenght = $em->getRepository('AppBundle:Telephonelog')
                ->countalltaskByName($namefilter);

        $findusers = $em->getRepository('LoginBundle:User')->findAll();
        $allusersarray = array(0 => ' ');
        foreach ($findusers as $us) {
            $allusersarray[$us->getUsername()] = $us->getUsername();
        }
        $to_remove = array('admin');
        $usersarray = array_diff($allusersarray, $to_remove);
        $usersarray['Office'] = 'Office';
        $limit = 50;
        $pages = ceil($loglenght[0][1] / $limit);
        $offset = ($page * $limit) - $limit;

        $allog = $em->getRepository('AppBundle:Telephonelog')
                ->findtaskbyPage($offset, $limit, $namefilter);
        $log = new Telephonelog();

        $form2 = $this->createFormBuilder($log)
                ->add('customerName', 'text', array(
                    'label' => 'Name', 'attr' => array('class' => 'input-sm', "autocomplete" => "new-password")
                ))
                ->add('customerTel', 'text', array(
                    'label' => 'Phone', 'attr' => array('class' => 'input-sm', 'placeholder' => '07xxxxxx')
                ))
                ->add('leadid', 'text', array('required' => false,
                    'attr' => array('class' => 'input-sm', 'style' => 'display:none;')
                ))
                ->add('customerEmail', 'text', array(
                    'label' => 'Email', 'required' => false, 'attr' => array('class' => 'input-sm', 'placeholder' => 'optional')
                ))
                ->add('deadline', 'date', [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control input-inline datepicker',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'placeholder' => 'dd-mm-yyyy',
                        'class' => 'input-sm'
                    ]
                ])
                ->add('inorout', 'text', array('read_only' => true, 'data' => 'new task', 'label' => 'Task', 'attr' => array('class' => 'input-sm')))
                ->add('note', 'textarea', array(
                    'label' => 'Note', 'attr' => array('class' => 'input-sm')
                ))
                ->add('action', 'textarea', array(
                    'required' => false, 'label' => 'Action', 'attr' => array('class' => 'input-sm')
                ))
                ->add('request', 'text', array(
                    'required' => false, 'label' => 'Request', 'attr' => array('class' => 'input-sm')
                ))
                ->add('reassign', 'choice', array(
                    'label' => 'Assign',
                    'choices' => $usersarray, 'attr' => array('class' => 'input-sm')
                ))
                ->add('flag', 'checkbox', array(
                    'label' => 'Flag', 'required' => false, 'attr' => array('required' => false,)
                ))
                ->add('solved', 'checkbox', array(
                    'label' => 'Solved', 'required' => false, 'attr' => array('required' => false,)
                ))
                ->add('save', 'submit', array(
                    'label' => 'save', 'attr' => array('class' => 'btn-success btn-md')))
                ->getForm();
        $form2->handleRequest($request);
        if ($form2->isValid()) {
            $log = $form2->getData();
            $logstatus = $log->getSolved();
            if ($logstatus) {
                $log->setSolved('1');
            } else {
                $log->setSolved('0');
            }
            $reassign = $log->getReassign();
            if ($reassign == '0') {
                $log->setReassign($name);
            }
            $log->setCreatedAt(new \DateTime());
            $log->setAssign($name);
            $em->persist($log);
            $em->flush();

            $leadid = $log->getLeadid();
            if ($leadid) {
                $callhistory = new Callhistory();
                $callhistory->setCalldate(new \DateTime());
                $callhistory->setNote($log->getNote());
                $callhistory->setStatus($log->getInorout());
                $callhistory->setLeadid($log->getLeadid());
                $callhistory->setAssign($name);
                $em->persist($callhistory);
                $em->flush();
            }
            if ($reassign) {
                $logID = $em->getRepository('AppBundle:Telephonelog')
                        ->findlastid();
                $note = $log->getNote();

                $notetoCollegue = $log->getRequest();
                $message = new Message();
                $message->setLogid($logID);
                if ($leadid) {
                    $message->setLeadid($leadid);
                }
                $message->setUsername($name);
                $message->setSubject('New task from:' . $name);
                $message->setBody($note . '     (Request to collegue:)  ' . $notetoCollegue);
                $message->setCustomerName($log->getCustomerName());
                $message->setDeadline($log->getDeadline());
                $message->setAssign($reassign);
                $message->setCreatedAt(new \DateTime());
                $em->persist($message);
                $em->flush();
            }
            return $this->redirectToRoute('log_todolist');
        }

        //pages array
        $pagediff = $pages - $page;
        if ($pagediff < 0) {
            $page = 1;
        }
        $pagesarray = array();
        for ($i = 1; $i <= $pages; $i++) {
            $pagesarray[] = $i;
        }
        if ($page <= '5') {
            $pagesarray = array_slice($pagesarray, 0, 10);
        }
        if ($page > '5') {
            $pagesarray = array_slice($pagesarray, $page - 5, 10);
        }
        //scrol down or up in pages
        if ($pages == $page) {
            $pageup = false;
        } else {
            $pageup = $page + 1;
        }
        if ($page == '1') {
            $pagedown = false;
        } else {
            $pagedown = $page - 1;
        }

        return $this->render('AppBundle:Log:logtodo.html.twig', array('namefilter' => $namefilter, 'usersarray' => $usersarray, 'pagedown' => $pagedown, 'pageup' => $pageup, 'itemperpage' => $limit, 'page' => $page, 'pagesarray' => $pagesarray, 'allog' => $allog, 'countlog' => $countlog, 'name' => $name, 'form2' => $form2->createView(),));
    }

    public function logAction(Request $request, $page, $itemperpage, $namefilter) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlog = $this->countlog();
        $this->userLogRegisterAction('Log main page opened');

        $em = $this->getDoctrine()->getManager();
        $findusers = $em->getRepository('LoginBundle:User')->findAll();
        $allusersarray = array(0 => ' ');
        foreach ($findusers as $us) {
            $allusersarray[$us->getUsername()] = $us->getUsername();
        }
        $to_remove = array('admin');
        $usersarray = array_diff($allusersarray, $to_remove);
        $usersarray['Office'] = 'Office';
        $loglenght = $em->getRepository('AppBundle:Telephonelog')
                ->countallByName($namefilter);
        $pages = ceil($loglenght[0][1] / $itemperpage);
        $limit = $itemperpage;
        $offset = $loglenght[0][1] - ($page * $itemperpage);
        if ($offset < 0) {
            $offset = 0;
            $limit = $loglenght[0][1] - (($page - 1) * $itemperpage);
        }
        $allogarray = $em->getRepository('AppBundle:Telephonelog')
                ->findLogbyPage($offset, $limit, $namefilter);
        $allograw = array_reverse($allogarray);
        $log = new Telephonelog();
        $form2 = $this->createFormBuilder($log)
                ->add('customerName', 'text', array(
                    'label' => 'Name', 'attr' => array('class' => 'input-sm', "autocomplete" => "new-password")
                ))
                ->add('customerTel', 'text', array(
                    'label' => 'Phone', 'attr' => array('class' => 'input-sm', 'placeholder' => '07xxxxxx')
                ))
                ->add('leadid', 'text', array('required' => false,
                    'attr' => array('class' => 'input-sm', 'style' => 'display:none;')
                ))
                ->add('customerEmail', 'text', array(
                    'label' => 'Email', 'required' => false, 'attr' => array('class' => 'input-sm', 'placeholder' => 'optional')
                ))
                ->add('deadline', 'date', [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control input-inline',
                        'data-provide' => 'datepicker',
                        'id' => 'datepicker', 
                        'data-date-format' => 'dd-mm-yyyy',
                        'placeholder' => 'dd-mm-yyyy',
                        'class' => 'input-sm'
                    ]
                ])
                ->add('inorout', 'choice', array(
                    'label' => 'In/Out',
                    'choices' => array('in' => 'in', 'out' => 'out', 'new task' => 'new task',), 'attr' => array('class' => 'input-sm')
                ))
                ->add('note', 'textarea', array(
                    'label' => 'Note', 'attr' => array('class' => 'input-sm')
                ))
                ->add('action', 'textarea', array(
                    'required' => false, 'label' => 'Action', 'attr' => array('class' => 'input-sm')
                ))
                ->add('request', 'text', array(
                    'required' => false, 'label' => 'Request', 'attr' => array('class' => 'input-sm')
                ))
                ->add('reassign', 'choice', array(
                    'label' => 'Assign',
                    'choices' => $usersarray, 'attr' => array('class' => 'input-sm')
                ))
                ->add('flag', 'checkbox', array(
                    'label' => 'Flag', 'required' => false, 'attr' => array('required' => false,)
                ))
                ->add('solved', 'checkbox', array(
                    'label' => 'Solved', 'required' => false, 'attr' => array('required' => false,)
                ))
                ->add('save', 'submit', array(
                    'label' => 'save', 'attr' => array('class' => 'btn-success btn-md')))
                ->getForm();
        $form2->handleRequest($request);
        if ($form2->isValid()) {
            $log = $form2->getData();
            $logstatus = $log->getSolved();
            if ($logstatus) {
                $log->setSolved('1');
            } else {
                $log->setSolved('0');
            }
            $reassign = $log->getReassign();
            if ($reassign == '0') {
                $log->setReassign($name);
            }
            $log->setCreatedAt(new \DateTime());
            $log->setAssign($name);
            $em->persist($log);
            $em->flush();

            $leadid = $log->getLeadid();
            if ($leadid) {
                $callhistory = new Callhistory();
                $callhistory->setCalldate(new \DateTime());
                $callhistory->setNote($log->getNote());
                $callhistory->setStatus($log->getInorout());
                $callhistory->setLeadid($log->getLeadid());
                $callhistory->setAssign($name);
                $em->persist($callhistory);
                $em->flush();
            }
            if ($reassign) {
                $logID = $em->getRepository('AppBundle:Telephonelog')
                        ->findlastid();
                $note = $log->getNote();
                $notetoCollegue = $log->getRequest();
                $message = new Message();
                $message->setLogid($logID);
                if ($leadid) {
                    $message->setLeadid($leadid);
                }
                $message->setUsername($name);
                $message->setSubject('New task from:' . $name);
                $message->setBody($note . '   (Request to collegue:) ' . $notetoCollegue);
                $message->setCustomerName($log->getCustomerName());
                $message->setDeadline($log->getDeadline());
                $message->setAssign($reassign);
                $message->setCreatedAt(new \DateTime());
                $em->persist($message);
                $em->flush();
            }
            return $this->redirectToRoute('log_main');
        }

        $pagediff = $pages - $page;
        if ($pagediff < 0) {
            $page = 1;
        }
        $pagesarray = array();
        for ($i = 1; $i <= $pages; $i++) {
            $pagesarray[] = $i;
        }
        if ($page <= '5') {
            $pagesarray = array_slice($pagesarray, 0, 10);
        }
        if ($page > '5') {
            $pagesarray = array_slice($pagesarray, $page - 5, 10);
        }

        $allog = $allograw;
        //scrol down or up in pages
        if ($pages == $page) {
            $pageup = false;
        } else {
            $pageup = $page + 1;
        }
        if ($page == '1') {
            $pagedown = false;
        } else {
            $pagedown = $page - 1;
        }
        return $this->render('AppBundle:Log:log.html.twig', array('namefilter' => $namefilter, 'usersarray' => $usersarray, 'pagedown' => $pagedown, 'pageup' => $pageup, 'itemperpage' => $itemperpage, 'page' => $page, 'pagesarray' => $pagesarray, 'allog' => $allog, 'countlog' => $countlog, 'name' => $name, 'form2' => $form2->createView(),));
    }

    public function logprogressAction(Request $request, $id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlog = $this->countlog();

        $em = $this->getDoctrine()->getManager();
        $thislog = $em->getRepository('AppBundle:Telephonelog')
                ->findBy(array('id' => $id));

        //User register
        $customerName = $thislog[0]->getCustomerName();
        $register = $customerName . ' log progress opened';
        $this->userLogRegisterAction($register);

        //find all log
        //get Loghistory
        $loghistory = $em->getRepository('AppBundle:Loghistory')
                ->findmyloghistorybyid($id);
        if ($request->getMethod() == "POST") {

            $note = $request->get("note");
            $logid = $request->get("logid");
            $status = $request->get("status");
            $flag = $request->get("flag");
            // note form handling
            if ($note != '') {
                $loghistory = new Loghistory();
                $loghistory->setLogid($logid);
                $loghistory->setNote($note);
                if ($status === 'solved') {
                    $loghistory->setStatus($status);
                }
                if ($status !== 'solved') {
                    $loghistory->setStatus('not solved');
                }
                $loghistory->setCalldate(new \DateTime());
                $loghistory->setAssign($name);
                $em->persist($loghistory);
                $em->flush();
            }
            if ($note == '') {
                if ($status === 'solved') {
                    $getlog = $em->getRepository('AppBundle:Telephonelog')->findOneBy(array('id' => $logid));
                    $checksolved = $getlog->getSolved();
                    if ($checksolved == '0') {
                        $getlog->setSolved('1');
                        $em->flush();
                        $loghistory = new Loghistory();
                        $loghistory->setLogid($logid);
                        $loghistory->setNote('Log status changed to solved');
                        $loghistory->setAssign($name);
                        $loghistory->setCalldate(new \DateTime());
                        $loghistory->setStatus('solved');
                        $em->persist($loghistory);
                        $em->flush();
                    }
                }
                if ($status == '0') {
                    $getlog = $em->getRepository('AppBundle:Telephonelog')->findOneBy(array('id' => $logid));
                    $checksolved = $getlog->getSolved();
                    if ($checksolved == '1') {
                        $getlog->setSolved('0');
                        $em->flush();
                        $loghistory = new Loghistory();
                        $loghistory->setLogid($logid);
                        $loghistory->setNote('Log status changed to NOT solved');
                        $loghistory->setAssign($name);
                        $loghistory->setCalldate(new \DateTime());
                        $loghistory->setStatus('not solved');
                        $em->persist($loghistory);
                        $em->flush();
                    }
                }
            }

            //flag form handling
            if ($flag === 'flag') {
                $getlog = $em->getRepository('AppBundle:Telephonelog')->findOneBy(array('id' => $logid));
                $getlog->setFlag('1');
                $em->flush();
            }
            if ($flag === '0') {
                $getlog = $em->getRepository('AppBundle:Telephonelog')->findOneBy(array('id' => $logid));
                $getlog->setFlag('0');
                $em->flush();
            }

            //reminder handling
            $remindernote = $request->get("remindernote");
            $reminderdate = $request->get("reminderdate");
            $reminderid = $request->get("id");
            if ($remindernote) {
                $em = $this->getDoctrine()->getManager();
                $lead = $em->getRepository('AppBundle:Telephonelog')->findOneBy(array('id' => $reminderid));
                $lead->setRemindernote($remindernote);
                $em->flush();
            }
            if ($reminderdate) {
                $em = $this->getDoctrine()->getManager();
                $lead = $em->getRepository('AppBundle:Telephonelog')->findOneBy(array('id' => $reminderid));
                $lead->setReminder(new \DateTime($reminderdate));
                $em->flush();
            }
            $deletereminderid = $request->get("deletereminderid");
            if ($deletereminderid) {
                $em = $this->getDoctrine()->getManager();
                $lead = $em->getRepository('AppBundle:Telephonelog')->findOneBy(array('id' => $deletereminderid));
                $lead->setReminder(null);
                $lead->setRemindernote(null);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('log_progress', array('id' => $id)));
        }
        return $this->render('AppBundle:Log:logprogress.html.twig', array('loghistory' => $loghistory, 'allog' => $thislog, 'countlog' => $countlog, 'name' => $name,));
    }

    public function logmyunsolvedAction() {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlog = $this->countlog();
        $this->userLogRegisterAction('Log my unsolved task opened');
        $em = $this->getDoctrine()->getManager();


        //find my unsolved log
        $myunarray = $em->getRepository('AppBundle:Telephonelog')
                ->findmyunsolved('0', $name);
        $myun = array_reverse($myunarray);

        return $this->render('AppBundle:Log:logmyunsolved.html.twig', array('myun' => $myun, 'countlog' => $countlog, 'name' => $name,));
    }

    public function logunsolvedAction() {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlog = $this->countlog();
        $this->userLogRegisterAction('Log all unsolved task opened');
        $em = $this->getDoctrine()->getManager();

        //find my unsolved log
        $allunarray = $em->getRepository('AppBundle:Telephonelog')
                ->findunsolved('0');
        $allun = array_reverse($allunarray);

        return $this->render('AppBundle:Log:logunsolved.html.twig', array('allun' => $allun, 'countlog' => $countlog, 'name' => $name,));
    }

    public function logalertAction() {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlog = $this->countlog();

        $em = $this->getDoctrine()->getManager();

        //find my unsolved log
        $allunarray = $em->getRepository('AppBundle:Telephonelog')
                ->findalert('0');
        $allun = array_reverse($allunarray);

        return $this->render('AppBundle:Log:logalert.html.twig', array('allun' => $allun, 'countlog' => $countlog, 'name' => $name,));
    }

    public function logflaggedAction() {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlog = $this->countlog();
        $this->userLogRegisterAction('Log flagged task opened');
        $em = $this->getDoctrine()->getManager();

        //find my unsolved log
        $flagarray = $em->getRepository('AppBundle:Telephonelog')
                ->findflagged('1', $name);
        $flagged = array_reverse($flagarray);

        return $this->render('AppBundle:Log:logflagged.html.twig', array('allun' => $flagged, 'countlog' => $countlog, 'name' => $name,));
    }

    public function logreminderAction() {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlog = $this->countlog();

        $em = $this->getDoctrine()->getManager();


        //today or expired reminder
        $reminderarray = $em->getRepository('AppBundle:Telephonelog')
                ->findreminder();
        $reminder = array_reverse($reminderarray);

        //all reminder
        $allreminderarray = $em->getRepository('AppBundle:Telephonelog')
                ->findallreminder();
        $allreminder = array_reverse($allreminderarray);

        return $this->render('AppBundle:Log:logreminder.html.twig', array('allreminder' => $allreminder, 'reminder' => $reminder, 'countlog' => $countlog, 'name' => $name,));
    }

    public function countlog() {

        $login = $this->getUser();
        $name = $login->getUsername();

        $countlog = array();
        $em = $this->getDoctrine()->getManager();

        $alllog = $em->getRepository('AppBundle:Telephonelog')
                ->countall();

        $flagged = $em->getRepository('AppBundle:Telephonelog')
                ->countflagged('1');

        $myunsolved = $em->getRepository('AppBundle:Telephonelog')
                ->countmyunsolved('0', $name);

        $allunsolved = $em->getRepository('AppBundle:Telephonelog')
                ->countallunsolved('0');

        $unreadmessage = $em->getRepository('AppBundle:Message')
                ->countUnread($name);

        $countlog[] = $alllog;
        $countlog[] = $flagged;
        $countlog[] = $myunsolved;
        $countlog[] = $allunsolved;
        $countlog[] = $unreadmessage;
        return $countlog;
    }

    public function logeditAction(Request $request, $id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlog = $this->countlog();

        //find users
        $em = $this->getDoctrine()->getManager();
        $findusers = $em->getRepository('LoginBundle:User')->findAll();
        $usersarray = array(0 => ' ');
        foreach ($findusers as $us) {
            $usersarray[$us->getUsername()] = $us->getUsername();
        }

        //edit lead details form
        $em = $this->getDoctrine()->getManager();
        $oldlog = $em->getRepository('AppBundle:Telephonelog')->find($id);
        $oldname = $oldlog->getCustomerName();
        $oldfirstname = $oldlog->getFirstname();
        $oldsurname = $oldlog->getSurname();
        $oldemail = $oldlog->getCustomerEmail();
        $oldphone = $oldlog->getCustomerTel();
        $olddeadline = $oldlog->getDeadline();
        $oldnote = $oldlog->getNote();
        $oldaction = $oldlog->getAction();
        $oldrequest = $oldlog->getRequest();
        $olddob = $oldlog->getDob();

        //User register
        $register = $oldname . ' Log details has been edited';
        $this->userLogRegisterAction($register);

        $log = new Telephonelog();

        $form2 = $this->createFormBuilder($log)
                ->add('customerName', 'text', array(
                    'label' => 'Name', 'data' => $oldname, 'attr' => array('class' => 'input-sm')
                ))
                ->add('firstname', 'text', array(
                    'required' => false, 'label' => 'Firstname', 'data' => $oldfirstname, 'attr' => array('class' => 'input-sm')
                ))
                ->add('surname', 'text', array(
                    'required' => false, 'label' => 'Surname', 'data' => $oldsurname, 'attr' => array('class' => 'input-sm')
                ))
                ->add('customerEmail', 'email', array(
                    'label' => 'Email', 'data' => $oldemail, 'attr' => array('class' => 'input-sm')
                ))
                ->add('customerTel', 'text', array(
                    'label' => 'Phone', 'data' => $oldphone, 'attr' => array('class' => 'input-sm')
                ))
                ->add('dob', 'date', [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'data' => $olddob,
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control input-inline datepicker',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'placeholder' => 'dd-mm-yyyy'
                    ]
                ])
                ->add('deadline', 'date', [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'data' => $olddeadline,
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control input-inline datepicker',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'placeholder' => 'dd-mm-yyyy'
                    ]
                ])
                ->add('inorout', 'choice', array(
                    'label' => 'In/Out',
                    'choices' => array('in' => 'in', 'out' => 'out', 'new task' => 'new task'), 'attr' => array('class' => 'input-sm')
                ))
                ->add('note', 'text', array(
                    'label' => 'Note', 'data' => $oldnote, 'attr' => array('class' => 'input-sm')
                ))
                ->add('action', 'text', array(
                    'label' => 'Aaction taken', 'data' => $oldaction, 'attr' => array('class' => 'input-sm')
                ))
                ->add('request', 'text', array(
                    'label' => 'Request to colleque', 'data' => $oldrequest, 'attr' => array('class' => 'input-sm')
                ))
                ->add('reassign', 'choice', array(
                    'label' => 'Assign to',
                    'choices' => $usersarray, 'attr' => array('class' => 'input-sm')
                ))
                ->add('save', 'submit', array(
                    'label' => 'save', 'attr' => array('class' => 'btn-success btn-md')))
                ->getForm();
        $form2->handleRequest($request);

        if ($form2->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $editlog = $form2->getData();
            $log = $em->getRepository('AppBundle:Telephonelog')->find($id);
            if ($log) {
                $log->setCustomerName($editlog->getCustomerName());
                $log->setCustomerEmail($editlog->getCustomerEmail());
                $log->setCustomerTel($editlog->getCustomerTel());
                $log->setDob($editlog->getDob());
                $log->setDeadline($editlog->getDeadline());
                $log->setInorout($editlog->getInorout());
                $log->setNote($editlog->getNote());
                $log->setAction($editlog->getAction());
                $log->setRequest($editlog->getRequest());
                $log->setReassign($editlog->getReassign());
                $em->flush();
            }

            return $this->redirectToRoute('log_progress', array('id' => $id));
        }

        return $this->render('AppBundle:Log:logedit.html.twig', array('countlog' => $countlog, 'name' => $name, 'form2' => $form2->createView(),));
    }

    public function logsaveAction(Request $request, $id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlog = $this->countlog();

        //find users
        $em = $this->getDoctrine()->getManager();
        $findusers = $em->getRepository('LoginBundle:User')->findAll();
        $usersarray = array(0 => ' ');
        foreach ($findusers as $us) {
            $usersarray[$us->getUsername()] = $us->getUsername();
        }

        //edit lead details form
        $oldlog = $em->getRepository('AppBundle:Telephonelog')->find($id);
        $oldname = $oldlog->getCustomerName();
        $oldfirstname = $oldlog->getFirstname();
        $oldsurname = $oldlog->getSurname();
        $oldemail = $oldlog->getCustomerEmail();
        $oldphone = $oldlog->getCustomerTel();
        $olddeadline = $oldlog->getDeadline();
        $oldnote = $oldlog->getNote();
        $oldaction = $oldlog->getAction();
        $oldrequest = $oldlog->getRequest();
        $olddob = $oldlog->getDob();

        $log = new Telephonelog();

        $form2 = $this->createFormBuilder($log)
                ->add('customerName', 'text', array(
                    'label' => 'Name', 'data' => $oldname, 'attr' => array('class' => 'input-sm')
                ))
                ->add('firstname', 'text', array(
                    'required' => false, 'label' => 'Firstname', 'data' => $oldfirstname, 'attr' => array('class' => 'input-sm')
                ))
                ->add('surname', 'text', array(
                    'required' => false, 'label' => 'Surname', 'data' => $oldsurname, 'attr' => array('class' => 'input-sm')
                ))
                ->add('customerEmail', 'email', array(
                    'required' => false, 'label' => 'Email', 'data' => $oldemail, 'attr' => array('class' => 'input-sm')
                ))
                ->add('customerTel', 'text', array(
                    'label' => 'Phone', 'data' => $oldphone, 'attr' => array('class' => 'input-sm')
                ))
                ->add('dob', 'date', [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'data' => $olddob,
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control input-inline datepicker',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'placeholder' => 'dd-mm-yyyy'
                    ]
                ])
                ->add('inorout', 'text', array(
                    'label' => 'New Lead',
                    'read_only' => true,
                    'data' => 'New Lead', 'attr' => array('class' => 'input-sm')
                ))
                ->add('note', 'text', array(
                    'label' => 'Note', 'data' => $oldnote, 'attr' => array('class' => 'input-sm')
                ))
                ->add('action', 'text', array(
                    'label' => 'Source', 'data' => 'Phone', 'attr' => array('class' => 'input-sm')
                ))
                ->add('save', 'submit', array(
                    'label' => 'save', 'attr' => array('class' => 'btn-success btn-md')))
                ->getForm();
        $form2->handleRequest($request);

        if ($form2->isValid()) {
            //User register
            $register = $oldname . ' Log has been saved as lead';
            $this->userLogRegisterAction($register);
            
            $lead = new Lead();
            $oldlog = $form2->getData();
            $callhistory = new Callhistory();
            $email = $oldlog->getCustomerEmail();
            if ($email) {
                $checkemail = $em->getRepository('AppBundle:Lead')->findOneBy(array('customerEmail' => $email));
                if (!$checkemail) {
                    $customername = $oldlog->getCustomerName();
                    $splitnamearray = $this->namesplit($customername);
                    $lead->setSurname($splitnamearray[1]);
                    $lead->setFirstname($splitnamearray[0]);
                    $lead->setCustomerName($oldlog->getCustomerName());
                    $lead->setCustomerEmail($oldlog->getCustomerEmail());
                    $lead->setCustomerTel($oldlog->getCustomerTel());
                    $lead->setMessage($oldlog->getNote());
                    $lead->setSource($oldlog->getAction());
                    $lead->setCreatedAt(new \DateTime());
                    $lead->setLastcontact(new \DateTime());
                    $lead->setStatus('new');
                    $lead->setAssign($name);
                    $lead->setContacted(1);
                    $em->persist($lead);
                    $em->flush();
                    $getbackid = $em->getRepository('AppBundle:Lead')->findlastid();
                    //$id = $getbackid->getId();
                    $callhistory->setLeadid($getbackid);
                    $callhistory->setNote($oldlog->getNote());
                    $callhistory->setCalldate(new \DateTime());
                    $callhistory->setAssign($name);
                    $callhistory->setStatus('Called');
                    $em->persist($callhistory);
                    $em->flush();
                    return $this->redirectToRoute('lead_progresstest', array('id' => $getbackid));
                } else {
                    $checkid = $checkemail->getId();
                    return $this->redirectToRoute('lead_progresstest', array('id' => $checkid));
                }
            } else {
                $customername = $oldlog->getCustomerName();
                $splitnamearray = $this->namesplit($customername);
                $lead->setSurname($splitnamearray[1]);
                $lead->setFirstname($splitnamearray[0]);
                $lead->setCustomerName($oldlog->getCustomerName());
                $lead->setCustomerTel($oldlog->getCustomerTel());
                $lead->setMessage($oldlog->getNote());
                $lead->setSource($oldlog->getAction());
                $lead->setCreatedAt(new \DateTime());
                $lead->setLastcontact(new \DateTime());
                $lead->setStatus('new');
                $lead->setAssign($name);
                $lead->setContacted(1);
                $em->persist($lead);
                $em->flush();
                $getbackid = $em->getRepository('AppBundle:Lead')->findOneBy(array('customerEmail' => $email));
                $id = $getbackid->getId();
                $callhistory->setLeadid($id);
                $callhistory->setNote($oldlog->getNote());
                $callhistory->setCalldate(new \DateTime());
                $callhistory->setAssign($name);
                $callhistory->setStatus('Called');
                $em->persist($callhistory);
                $em->flush();
                return $this->redirectToRoute('lead_newajax');
            }
        }

        return $this->render('AppBundle:Log:logsave.html.twig', array('countlog' => $countlog, 'name' => $name, 'form2' => $form2->createView(),));
    }

    public function logsearch($formsearch) {

        $result = array();
        if ($formsearch->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $formsearch->getData();
            //if name field has item it will try to find in the databae
            if ($data['name']) {
                //$em = $this->getDoctrine()->getManager();
                $namesearch = $em->getRepository('AppBundle:Telephonelog')
                        ->searchlogByName($data['name']);
                if (!$namesearch) {
                    $namesearch = '';
                }
                $result[] = $namesearch;

                //emailsearch
                $emailsearch = $em->getRepository('AppBundle:Telephonelog')
                        ->searchlogByEmail($data['name']);
                if (!$emailsearch) {
                    $emailsearch = '';
                }
                $result[] = $emailsearch;

                //tel search
                $telsearch = $em->getRepository('AppBundle:Telephonelog')
                        ->searchlogByPhone($data['name']);
                if (!$telsearch) {
                    $telsearch = '';
                }
                $result[] = $telsearch;
            }
        }
        return $result;
    }

    public function namesplit($customername) {
        $restul = array();
        $split = explode(" ", $customername);
        $surename = array_pop($split);
        $firstname = implode(" ", $split);
        $result[0] = $firstname;
        $result[1] = $surename;
        return $result;
    }

}
