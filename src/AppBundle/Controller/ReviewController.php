<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Rtemplate;
use AppBundle\Entity\Reviewsent;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller {

    public function reviewAction(Request $request, $success, $id, $logid) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $tempid = false;
        $error = false;
        if ($success === 0) {
            $success = false;
        }

        if ($id === 0 and $logid === 0) {
            $email = '';
            $cname = '';
        } elseif ($id != 0) {
            $thislead = $em->getRepository('AppBundle:Lead')
                    ->findOneBy(array('id' => $id));
            $cname = $thislead->getCustomerName();
            $email = $thislead->getCustomerEmail();
        } elseif ($logid != 0) {
            $thislead = $em->getRepository('AppBundle:Telephonelog')
                    ->findOneBy(array('id' => $logid));
            $cname = $thislead->getCustomerName();
            $email = $thislead->getCustomerEmail();
        }

        $dlist = $em->getRepository('AppBundle:Doctorlist')
                ->findAll();
        $dlistarray = array();
        foreach ($dlist as $dl) {
            $dlistarray[$dl->getId()] = $dl->getDname();
        }

        $templist = $em->getRepository('AppBundle:Rtemplate')->findAll();
        $tempnamearray = array();
        foreach ($templist as $temp) {
            $tempnamearray[$temp->getId()] = $temp->getTempname();
        }
        $settings = $em->getRepository('AppBundle:Settings')
                ->findallactive('1');
        $settingsarray = array();
        foreach ($settings as $sett) {
            $settingsarray[$sett->getId()] = $sett->getEusername();
        }

        if ($request->getMethod() == "POST") {
            $oldemail = $request->get("mailemail");
            $oldcname = $request->get("mailname");
            $settid = $request->get("settid");
            $tempid = $request->get("tempid");
            $subject = $request->get("subject");
            $messagesend = $request->get("editor1");
            //$applenght = $request->get("applengthsend");
            $doctor = $request->get("doctorsend");
            $thissettings = $em->getRepository('AppBundle:Settings')
                    ->findOneBy(array('id' => $settid));
            $fromname = $thissettings->getFromname();

            $thistemplate = $em->getRepository('AppBundle:Rtemplate')
                    ->findOneBy(array('id' => $tempid));
            $attid1 = $thistemplate->getAttach1();
            $attid2 = $thistemplate->getAttach2();
            $attid3 = $thistemplate->getAttach3();

            //trim email and customer name
            $trimmedemail = strtolower(trim($oldemail));
            $email = str_replace(array("\r", "\n"), '', $trimmedemail);

            $trimmedcname = trim($oldcname);
            $cname = str_replace(array("\r", "\n"), '', $trimmedcname);

            // find Attachment1
            if ($attid1 != 0) {
                $thisattach1 = $em->getRepository('AppBundle:Product')
                        ->findOneBy(array('id' => $attid1));
                $attachname1 = $thisattach1->getImageName();
            } else {
                $attachname1 = false;
                $thisattach1 = false;
            }
            $path1 = false;
            if ($thisattach1) {
                $attName1 = $thisattach1->getImageName();
                $attPath1 = $thisattach1->getPath();
                $path1 = $attPath1 . $attName1;
            }

            // find Attachment2
            if ($attid2 != 0) {
                $thisattach2 = $em->getRepository('AppBundle:Product')
                        ->findOneBy(array('id' => $attid2));
                $attachname2 = $thisattach2->getImageName();
            } else {
                $attachname2 = false;
                $thisattach2 = false;
            }
            $path2 = false;
            if ($thisattach2) {
                $attName2 = $thisattach2->getImageName();
                $attPath2 = $thisattach2->getPath();
                $path2 = $attPath2 . $attName2;
            }

            // find Attachment3
            if ($attid3 != 0) {
                $thisattach3 = $em->getRepository('AppBundle:Product')
                        ->findOneBy(array('id' => $attid3));
                $attachname3 = $thisattach3->getImageName();
            } else {
                $attachname3 = false;
                $thisattach3 = false;
            }
            $path3 = false;
            if ($thisattach3) {
                $attName3 = $thisattach3->getImageName();
                $attPath3 = $thisattach3->getPath();
                $path3 = $attPath3 . $attName3;
            }

            $smtp = $thissettings->getSmtp();
            $port = $thissettings->getPort();
            $mssl = $thissettings->getEssl();
            $euser = $thissettings->getEusername();
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
            $message = \Swift_Message::newInstance($subject)
                    ->setFrom(array($euser => $fromname))
                    ->setTo(array($email => $cname))
                    ->setBody($messagesend, 'text/html')
            ;
            if ($path1) {
                $message->attach(\Swift_Attachment::fromPath($path1));
            }
            if ($path2) {
                $message->attach(\Swift_Attachment::fromPath($path2));
            }
            if ($path3) {
                $message->attach(\Swift_Attachment::fromPath($path3));
            }
            try {
                $mailer->getTransport()->start();
                $mailer->send($message);
                $mailer->getTransport()->stop();
                $reviewsent = new Reviewsent();
                $reviewsent->setCreatedAt(new \DateTime());
                $reviewsent->setUser($name);
                $reviewsent->setCustomerName($cname);
                $reviewsent->setCustomerEmail($email);
                $reviewsent->setFromEmail($euser);
                $reviewsent->setBody($messagesend);
                $reviewsent->setDoctor($doctor);
                $reviewsent->setTempid($tempid);
                if ($id != 0) {
                    $reviewsent->setLeadid($id);
                }
                if ($logid != 0) {
                    $reviewsent->setLogid($logid);
                }
                $em->persist($reviewsent);
                $em->flush();
            } catch (Swift_TransportException $e) {
                $mailer->getTransport()->stop();
                $error = true;
                return $this->render('AppBundle:Review:reviewsuccess.html.twig', array('success' => $success, 'error' => $error, 'name' => $name, 'dlist' => $dlist,));
            } catch (Exception $e) {
                $mailer->getTransport()->stop();
                $error = true;
                return $this->render('AppBundle:Review:reviewsuccess.html.twig', array('success' => $success, 'error' => $error, 'name' => $name, 'dlist' => $dlist,));
            }
            $success = 'success';
            return $this->redirect($this->generateUrl('review_main', array('success' => $success,)));
        }

        return $this->render('AppBundle:Review:reviewmain.html.twig', array(
                    'dlist' => $dlist, 'dlistarray' => $dlistarray, 'temparray' => $tempnamearray, 'id' => $id, 'name' => $name, 'email' => $email, 'tempid' => $tempid, 'cname' => $cname, 'settingsarray' => $settingsarray, 'error' => $error, 'success' => $success,));
    }

    public function consfeedbackAction(Request $request, $docid, $success, $id, $logid) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $tempid = false;

        $error = false;
        if ($success === 0) {
            $success = false;
        }

        if ($id === 0 and $logid === 0) {
            $email = '';
            $cname = '';
        } elseif ($id != 0) {
            $thislead = $em->getRepository('AppBundle:Lead')
                    ->findOneBy(array('id' => $id));
            $cname = $thislead->getCustomerName();
            $email = $thislead->getCustomerEmail();
        } elseif ($logid != 0) {
            $thislead = $em->getRepository('AppBundle:Telephonelog')
                    ->findOneBy(array('id' => $logid));
            $cname = $thislead->getCustomerName();
            $email = $thislead->getCustomerEmail();
        }

        $dlist = $em->getRepository('AppBundle:Doctorlist')
                ->findAll();
        $doctor = $em->getRepository('AppBundle:Doctorlist')
                ->find($docid);
        $template = $em->getRepository('AppBundle:Rtemplate')
                ->find($doctor->getFeedbackid());

        $settings = $em->getRepository('AppBundle:Settings')
                ->findallactive('1');
        $settingsarray = array();
        foreach ($settings as $sett) {
            $settingsarray[$sett->getId()] = $sett->getEusername();
        }

        if ($request->getMethod() == "POST") {
            $oldemail = $request->get("mailemail");
            $oldcname = $request->get("mailname");
            $settid = $request->get("settid");
            $tempid = $request->get("tempid");
            $subject = $request->get("subject");
            $messagesend = $request->get("editor1");
            $doctorstring = $request->get("doctorsend");
            $thissettings = $em->getRepository('AppBundle:Settings')
                    ->findOneBy(array('id' => $settid));
            $fromname = $thissettings->getFromname();

            $thistemplate = $em->getRepository('AppBundle:Rtemplate')
                    ->findOneBy(array('id' => $tempid));
            $attid1 = $thistemplate->getAttach1();
            $attid2 = $thistemplate->getAttach2();
            $attid3 = $thistemplate->getAttach3();

            //trim email and customer name
            $trimmedemail = strtolower(trim($oldemail));
            $email = str_replace(array("\r", "\n"), '', $trimmedemail);

            $trimmedcname = trim($oldcname);
            $cname = str_replace(array("\r", "\n"), '', $trimmedcname);

            // find Attachment1
            if ($attid1 != 0) {
                $thisattach1 = $em->getRepository('AppBundle:Product')
                        ->findOneBy(array('id' => $attid1));
                $attachname1 = $thisattach1->getImageName();
            } else {
                $attachname1 = false;
                $thisattach1 = false;
            }
            $path1 = false;
            if ($thisattach1) {
                $attName1 = $thisattach1->getImageName();
                $attPath1 = $thisattach1->getPath();
                $path1 = $attPath1 . $attName1;
            }

            // find Attachment2
            if ($attid2 != 0) {
                $thisattach2 = $em->getRepository('AppBundle:Product')
                        ->findOneBy(array('id' => $attid2));
                $attachname2 = $thisattach2->getImageName();
            } else {
                $attachname2 = false;
                $thisattach2 = false;
            }
            $path2 = false;
            if ($thisattach2) {
                $attName2 = $thisattach2->getImageName();
                $attPath2 = $thisattach2->getPath();
                $path2 = $attPath2 . $attName2;
            }

            // find Attachment3
            if ($attid3 != 0) {
                $thisattach3 = $em->getRepository('AppBundle:Product')
                        ->findOneBy(array('id' => $attid3));
                $attachname3 = $thisattach3->getImageName();
            } else {
                $attachname3 = false;
                $thisattach3 = false;
            }
            $path3 = false;
            if ($thisattach3) {
                $attName3 = $thisattach3->getImageName();
                $attPath3 = $thisattach3->getPath();
                $path3 = $attPath3 . $attName3;
            }

            $smtp = $thissettings->getSmtp();
            $port = $thissettings->getPort();
            $mssl = $thissettings->getEssl();
            $euser = $thissettings->getEusername();
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
            $message = \Swift_Message::newInstance($subject)
                    ->setFrom(array($euser => $fromname))
                    ->setTo(array($email => $cname))
                    ->setBody($messagesend, 'text/html')
            ;
            if ($path1) {
                $message->attach(\Swift_Attachment::fromPath($path1));
            }
            if ($path2) {
                $message->attach(\Swift_Attachment::fromPath($path2));
            }
            if ($path3) {
                $message->attach(\Swift_Attachment::fromPath($path3));
            }
            try {
                $mailer->getTransport()->start();
                $mailer->send($message);
                $mailer->getTransport()->stop();
                $reviewsent = new Reviewsent();
                $reviewsent->setCreatedAt(new \DateTime());
                $reviewsent->setUser($name);
                $reviewsent->setCustomerName($cname);
                $reviewsent->setCustomerEmail($email);
                $reviewsent->setFromEmail($euser);
                $reviewsent->setBody($messagesend);
                //$confirmsent->setApplength($applenght);
                $reviewsent->setDoctor($doctorstring);
                $reviewsent->setTempid($tempid);
                if ($id != 0) {
                    $reviewsent->setLeadid($id);
                }
                if ($logid != 0) {
                    $reviewsent->setLogid($logid);
                }
                $em->persist($reviewsent);
                $em->flush();
            } catch (Swift_TransportException $e) {
                $mailer->getTransport()->stop();
                $error = true;
                return $this->render('AppBundle:Review:reviewsuccess.html.twig', array('success' => $success, 'error' => $error, 'name' => $name, 'dlist' => $dlist,));
            } catch (Exception $e) {
                $mailer->getTransport()->stop();
                $error = true;
                return $this->render('AppBundle:Review:reviewsuccess.html.twig', array('success' => $success, 'error' => $error, 'name' => $name, 'dlist' => $dlist,));
            }
            $success = 'success';
            return $this->redirect($this->generateUrl('review_consfeedback', array('docid' => $docid, 'success' => $success,)));
        }

        return $this->render('AppBundle:Review:reviewconsfeedback.html.twig', array(
                    'docid' => $docid, 'dlist' => $dlist, 'doctor' => $doctor, 'template' => $template, 'id' => $id, 'name' => $name, 'email' => $email, 'tempid' => $tempid, 'cname' => $cname, 'settingsarray' => $settingsarray, 'error' => $error, 'success' => $success,));
    }

    public function reviewtemplateAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $rtemplist = $em->getRepository('AppBundle:Rtemplate')->findAll();
        if (!$rtemplist) {
            $rtemplist = false;
        }
        $attachment = $em->getRepository('AppBundle:Product')
                ->findbylistname('attachment');
        $attachmentsarray = array();
        $attachmentsarray[] = '';
        foreach ($attachment as $temp) {
            $attachmentsarray[$temp->getId()] = $temp->getImageName();
        }

        $dlist = $em->getRepository('AppBundle:Doctorlist')
                ->findAll();

        $rtemplate = new Rtemplate();
        $form = $this->createFormBuilder($rtemplate)
                ->add('tempname', 'text', array(
                    'label' => 'Template name',))
                ->add('subject')
                ->add('attach1', 'choice', array(
                    'label' => 'Attachment 1',
                    'choices' => $attachmentsarray, 'attr' => array('class' => 'input-sm')))
                ->add('attach2', 'choice', array(
                    'label' => 'Attachment 2',
                    'choices' => $attachmentsarray, 'attr' => array('class' => 'input-sm')))
                ->add('attach3', 'choice', array(
                    'label' => 'Attachment 3',
                    'choices' => $attachmentsarray, 'attr' => array('class' => 'input-sm')))
                ->add('body', 'textarea', array('attr' => array('class' => 'ckeditor')))
                ->add('save', 'submit')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $rtemplate->setUpdatedAt();
            $rtemplate->setUsername($name);
            $em->persist($rtemplate);
            $em->flush();

            return $this->redirectToRoute('review_template');
        }
        return $this->render('AppBundle:Review:reviewtemplate.html.twig', array('rtemplist' => $rtemplist,
                    'dlist' => $dlist, 'form' => $form->createView(), 'name' => $name,
        ));
    }

    public function rTemplateEditAction(Request $request, $id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $oldattname1 = false;
        $oldattname2 = false;
        $oldattname3 = false;
        $rtemplist = $em->getRepository('AppBundle:Rtemplate')->find($id);
        if (!$rtemplist) {
            $rtemplist = false;
        }
        $attachment = $em->getRepository('AppBundle:Product')
                ->findbylistname('attachment');
        $attachmentsarray = array();
        $attachmentsarray[] = '';
        foreach ($attachment as $temp) {
            $attachmentsarray[$temp->getImageName()] = $temp->getImageName();
        }
        $oldtemp = $em->getRepository('AppBundle:Rtemplate')->find($id);
        $oldname = $oldtemp->getTempname();
        $oldsubject = $oldtemp->getSubject();
        $oldbody = $oldtemp->getBody();
        $oldatt1 = $oldtemp->getAttach1();
        if ($oldatt1 != 0) {
            $tempattach1 = $em->getRepository('AppBundle:Product')
                    ->find($oldatt1);
            $oldattname1 = $tempattach1->getImageName();
        }
        $oldatt2 = $oldtemp->getAttach2();
        if ($oldatt2 != 0) {
            $tempattach2 = $em->getRepository('AppBundle:Product')
                    ->find($oldatt2);
            $oldattname2 = $tempattach2->getImageName();
        }
        $oldatt3 = $oldtemp->getAttach3();
        if ($oldatt3 != 0) {
            $tempattach3 = $em->getRepository('AppBundle:Product')
                    ->find($oldatt3);
            $oldattname3 = $tempattach3->getImageName();
        }

        $dlist = $em->getRepository('AppBundle:Doctorlist')
                ->findAll();

        $rtemplist = new Rtemplate();

        $form = $this->createFormBuilder($rtemplist)
                ->add('tempname', 'text', array(
                    'label' => 'Template name', 'data' => $oldname, 'attr' => array('class' => 'input-sm')
                ))
                ->add('subject', 'text', array(
                    'label' => 'Subject', 'data' => $oldsubject, 'attr' => array('class' => 'input-sm')
                ))
                ->add('attach1', 'choice', array(
                    'label' => 'Attachment 1', 'required' => false, 'data' => $oldatt1,
                    'choices' => $attachmentsarray, 'attr' => array('class' => 'input-sm')))
                ->add('attach2', 'choice', array(
                    'label' => 'Attachment 2', 'required' => false, 'data' => $oldatt2,
                    'choices' => $attachmentsarray, 'attr' => array('class' => 'input-sm')))
                ->add('attach3', 'choice', array(
                    'label' => 'Attachment 3', 'required' => false, 'data' => $oldatt3,
                    'choices' => $attachmentsarray, 'attr' => array('class' => 'input-sm')))
                ->add('body', 'textarea', array(
                    'required' => false, 'label' => 'Message', 'data' => $oldbody, 'attr' => array('class' => 'ckeditor')
                ))
                ->add('save', 'submit', array(
                    'label' => 'save', 'attr' => array('class' => 'btn-success btn-md')))
                ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $edittemp = $form->getData();
            $template = $em->getRepository('AppBundle:Rtemplate')->find($id);
            if ($template) {
                $template->setTempname($edittemp->getTempname());
                $template->setSubject($edittemp->getSubject());
                $template->setBody($edittemp->getBody());

                $att1name = $edittemp->getAttach1();
                if ($att1name) {
                    $myatt1 = $em->getRepository('AppBundle:Product')
                            ->findOneBy(array('imageName' => $att1name));
                    $newattid1 = $myatt1->getId();
                    $template->setAttach1($newattid1);
                }
                $att2name = $edittemp->getAttach2();
                if ($att2name) {
                    $myatt2 = $em->getRepository('AppBundle:Product')
                            ->findOneBy(array('imageName' => $att2name));
                    $newattid2 = $myatt2->getId();
                    $template->setAttach2($newattid2);
                }
                $att3name = $edittemp->getAttach3();
                if ($att3name) {
                    $myatt3 = $em->getRepository('AppBundle:Product')
                            ->findOneBy(array('imageName' => $att3name));
                    $newattid3 = $myatt3->getId();
                    $template->setAttach3($newattid3);
                }
                $em->flush();
            }

            return $this->redirectToRoute('review_template');
        }

        return $this->render('AppBundle:Review:reviewtempedit.html.twig', array('rtemplist' => $rtemplist,
                    'dlist' => $dlist, 'id' => $id, 'oldattname1' => $oldattname1, 'oldattname2' => $oldattname2, 'oldattname3' => $oldattname3, 'form' => $form->createView(), 'name' => $name,
        ));
    }

    public function reviewseetemplateAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thisTemplate = $em->getRepository('AppBundle:Rtemplate')
                ->find($id);
        $attachname1 = false;
        $attachname2 = false;
        $attachname3 = false;
        $title = $thisTemplate->getTempname();
        $subject = $thisTemplate->getSubject();
        $body = $thisTemplate->getBody();
        $att1id = $thisTemplate->getAttach1();
        $att2id = $thisTemplate->getAttach2();
        $att3id = $thisTemplate->getAttach3();
        if ($att1id) {
            $attach1 = $em->getRepository('AppBundle:Product')->find($att1id);
            if ($attach1) {
                $attachname1 = $attach1->getImageName();
            }
        }
        if ($att2id) {
            $attach2 = $em->getRepository('AppBundle:Product')->find($att2id);
            if ($attach2) {
                $attachname2 = $attach2->getImageName();
            }
        }
        if ($att3id) {
            $attach3 = $em->getRepository('AppBundle:Product')->find($att3id);
            if ($attach3) {
                $attachname3 = $attach3->getImageName();
            }
        }
        return $this->render('AppBundle:Review:reviewSeeTemplate.html.twig', array(
                    'attachname3' => $attachname3, 'attachname2' => $attachname2, 'attachname1' => $attachname1, 'title' => $title, 'subject' => $subject, 'body' => $body,
        ));
    }

    public function rtemplatedeleteAction($id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $rtemplate = $em->getRepository('AppBundle:Rtemplate')->find($id);
        $em->remove($rtemplate);
        $em->flush();

        return $this->redirect($this->generateUrl('review_template'));
    }

    public function rtemplateduplicateAction($id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $etemplate = $em->getRepository('AppBundle:Rtemplate')->find($id);
        $newtemplate = new Rtemplate();
        $oldtempname = $etemplate->getTempname();
        $lenold = strlen($oldtempname);
        $newtempname = substr_replace($oldtempname, ' - ' . $name, $lenold);
        $newtemplate->setTempname($newtempname);
        $newtemplate->setSubject($etemplate->getSubject());
        $newtemplate->setBody($etemplate->getBody());
        $newtemplate->setUsername($name);
        $newtemplate->setUpdatedAt();
        $em->persist($newtemplate);
        $em->flush();

        return $this->redirect($this->generateUrl('review_template'));
    }

    public function ajaxrtempplatesubjectAction($id) {
        $em = $this->getDoctrine()->getManager();

        $rtemplate = $em->getRepository('AppBundle:Rtemplate')
                ->find($id);
        $subject = $rtemplate->getSubject();

        $response = new Response(json_encode($subject));
        return $response;
    }

    public function reviewajaxtemplatebodyAction($id) {
        $em = $this->getDoctrine()->getManager();

        $rtemplate = $em->getRepository('AppBundle:Rtemplate')
                ->find($id);
        $body = $rtemplate->getBody();
        $response = new Response(json_encode($body));
        return $response;
    }

    public function ajaxattachAction($id) {
        $em = $this->getDoctrine()->getManager();
        $attname1 = false;
        $attname2 = false;
        $attname3 = false;

        $ctemplate = $em->getRepository('AppBundle:Rtemplate')
                ->find($id);
        $attid1 = $ctemplate->getAttach1();
        if ($attid1 != 0) {
            $attach1 = $em->getRepository('AppBundle:Product')
                    ->findOneBy(array('id' => $attid1));
            $attname1 = $attach1->getImageName();
        }
        $attid2 = $ctemplate->getAttach2();
        if ($attid2 != 0) {
            $attach2 = $em->getRepository('AppBundle:Product')
                    ->findOneBy(array('id' => $attid2));
            $attname2 = $attach2->getImageName();
        }
        $attid3 = $ctemplate->getAttach3();
        if ($attid3 != 0) {
            $attach3 = $em->getRepository('AppBundle:Product')
                    ->findOneBy(array('id' => $attid3));
            $attname3 = $attach3->getImageName();
        }
        $html = $this->renderView('AppBundle:Confirm:confirmajaxtemplate.html.twig', array('attname1' => $attname1, 'attname2' => $attname2, 'attname3' => $attname3));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function reviewsentAction(Request $request, $page) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $countConfSent = $em->getRepository('AppBundle:Reviewsent')
                ->countall();
        $limit = 20;
        $pages = ceil($countConfSent / $limit);
        $offset = $countConfSent - ($limit * $page);
        if ($offset < 0) {
            $limit = $offset + $limit;
            $offset = 0;
        }
        $confirmationSentraw = $em->getRepository('AppBundle:Reviewsent')
                ->findConfSentbylimit($limit, $offset);
        $confirmationSent = array_reverse($confirmationSentraw);
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

        $dlist = $em->getRepository('AppBundle:Doctorlist')
                ->findAll();


        return $this->render('AppBundle:Review:reviewsent.html.twig', array(
                    'dlist' => $dlist, 'name' => $name, 'confsent' => $confirmationSent, 'pagesarray' => $pagesarray, 'pagedown' => $pagedown, 'pageup' => $pageup, 'page' => $page,));
    }

    public function reviewseesentAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thissent = $em->getRepository('AppBundle:Reviewsent')
                ->find($id);
        $attachname1 = false;
        $attachname2 = false;
        $attachname3 = false;
        $cName = $thissent->getCustomerName();
        $cEmail = $thissent->getCustomerEmail();
        $fromEmail = $thissent->getFromEmail();
        $createdAt = $thissent->getCreatedAt();
        $thisDate = $createdAt->format('d/m/Y H:i:s');
        $title = 'To:' . $cName . ' Email:' . $cEmail . ' From:' . $fromEmail . ' At:' . $thisDate;
        $body = $thissent->getBody();
        $tempid = $thissent->getTempId();
        $thisTemplate = $em->getRepository('AppBundle:Rtemplate')
                ->find($tempid);
        $subject = $thisTemplate->getSubject();
        $att1id = $thisTemplate->getAttach1();
        $att2id = $thisTemplate->getAttach2();
        $att3id = $thisTemplate->getAttach3();
        if ($att1id) {
            $attach1 = $em->getRepository('AppBundle:Product')->find($att1id);
            if ($attach1) {
                $attachname1 = $attach1->getImageName();
            }
        }
        if ($att2id) {
            $attach2 = $em->getRepository('AppBundle:Product')->find($att2id);
            if ($attach2) {
                $attachname2 = $attach2->getImageName();
            }
        }
        if ($att3id) {
            $attach3 = $em->getRepository('AppBundle:Product')->find($att3id);
            if ($attach3) {
                $attachname3 = $attach3->getImageName();
            }
        }
        return $this->render('AppBundle:Confirm:confirmSeeTemplate.html.twig', array(
                    'attachname3' => $attachname3, 'attachname2' => $attachname2, 'attachname1' => $attachname1, 'title' => $title, 'subject' => $subject, 'body' => $body,
        ));
    }

}
