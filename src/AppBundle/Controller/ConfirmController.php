<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Ctemplate;
use AppBundle\Entity\Etemplate;
use AppBundle\Entity\Rtemplate;
use AppBundle\Paginator\Paginator;
use AppBundle\Form\EtemplateType;
use AppBundle\Entity\Product;
use AppBundle\Entity\Doctorlist;
use AppBundle\Entity\Confirmsent;
use AppBundle\Entity\Texttemplate;
use AppBundle\Form\DoctorlistType;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Rest\Client;
use AppBundle\Entity\Textmessage;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ConfirmController extends Controller {

    public function confirmAction(Request $request, $id, $logid) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        //$sent = false;
        $tempid = false;
        $error = false;
        $success = false;
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

        $templist = $em->getRepository('AppBundle:Ctemplate')->findAll();
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


        //$Request = $this->getRequest();
        if ($request->getMethod() == "POST") {
            $oldemail = $request->get("mailemail");
            $oldcname = $request->get("mailname");
            $settid = $request->get("settid");
            $tempid = $request->get("tempid");
            $subject = $request->get("subject");
            $messagesend = $request->get("editor1");
            $applenght = $request->get("applengthsend");
            $appdate = $request->get("appdate");
            $apptime = $request->get("apptime");
            $doctor = $request->get("doctorsend");

            $appDateString = $appdate . ' ' . $apptime;

            $thissettings = $em->getRepository('AppBundle:Settings')
                    ->findOneBy(array('id' => $settid));
            $fromname = $thissettings->getFromname();

            $thistemplate = $em->getRepository('AppBundle:Ctemplate')
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
                $confirmsent = new Confirmsent();
                $confirmsent->setCreatedAt(new \DateTime());
                $confirmsent->setUser($name);
                $confirmsent->setCustomerName($cname);
                $confirmsent->setCustomerEmail($email);
                $confirmsent->setFromEmail($euser);
                $confirmsent->setBody($messagesend);
                $confirmsent->setApplength($applenght);
                $confirmsent->setAppDate($appDateString);
                $confirmsent->setDoctor($doctor);
                $confirmsent->setTempid($tempid);
                if ($id != 0) {
                    $confirmsent->setLeadid($id);
                }
                if ($logid != 0) {
                    $confirmsent->setLogid($logid);
                }
                $em->persist($confirmsent);
                $em->flush();
            } catch (Swift_TransportException $e) {
                $mailer->getTransport()->stop();
                $error = true;
                return $this->render('AppBundle:Confirm:confirmsuccess.html.twig', array('success' => $success, 'error' => $error, 'name' => $name,));
            } catch (Exception $e) {
                $mailer->getTransport()->stop();
                $error = true;
                return $this->render('AppBundle:Confirm:confirmsuccess.html.twig', array('success' => $success, 'error' => $error, 'name' => $name,));
            }
            $success = true;
            return $this->render('AppBundle:Confirm:confirmsuccess.html.twig', array('success' => $success, 'error' => $error, 'name' => $name,));
        }
        return $this->render('AppBundle:Confirm:confirm.html.twig', array('success' => $success, 'error' => $error, 'dlistarray' => $dlistarray, 'id' => $id, 'name' => $name, 'email' => $email, 'tempid' => $tempid, 'cname' => $cname, 'settingsarray' => $settingsarray, 'temparray' => $tempnamearray));
    }

    public function confirmtempAction(Request $request, $counter) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $limit = false;

        $custom = [];
        $form = $this->createFormBuilder($custom)
                ->add('start', 'integer')
                ->add('limit', 'integer')
                ->add('save', 'submit', array('label' => 'Submit'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $custom = $form->getData();

            $counter = $custom['start'];
            $limit = $custom['limit'];
        }

        if ($counter != 0) {
            $em = $this->getDoctrine()->getManager();

            if (!$limit) {
                $limit = 200;
            }
            $leadTempRaw = $em->getRepository('AppBundle:Lead')
                    ->findTempLead($limit, $counter);

            foreach ($leadTempRaw as $leadTemp) {
                $phone = $leadTemp->getCustomerTel();
                $phone = preg_replace('/\s/', '', $phone);
                $phone = str_replace(' ', '', $phone);

                if (strpos($phone, '+44') !== false) {
                    $position44 = strpos($phone, '+44');
                    $phone = substr($phone, $position44);
                }


                if ($phone) {
                    $checkPhone = substr($phone, 0, 2);
                    switch ($checkPhone) {
                        case '+4':
                            $newPhone = preg_replace('/\s/', '', $phone);
                            break;
                        case '07':
                            $tempPhone = substr($phone, 1);
                            $newPhone = '+44' . '' . $tempPhone;
                            $newPhone = preg_replace('/\s/', '', $newPhone);
                            break;
                        case '00':
                            $tempPhone = substr($phone, 2);
                            $newPhone = '+' . '' . $tempPhone;
                            $newPhone = preg_replace('/\s/', '', $newPhone);
                            break;
                        case '44':
                            $newPhone = '+' . '' . $phone;
                            $newPhone = preg_replace('/\s/', '', $newPhone);
                            break;
                        default:
                            //020... , 01... etc
                            $newCheck = substr($phone, 0, 1);
                            if ($newCheck == '0') {
                                $tempPhone = substr($phone, 1);
                                $newPhone = '+44' . '' . $tempPhone;
                                $newPhone = preg_replace('/\s/', '', $newPhone);
                                break;
                            } elseif ($newCheck == '+') {
                                //foreign number with + ... do nothing
                                $newPhone = preg_replace('/\s/', '', $phone);
                                break;
                            } else {
                                //7..... zero is missing from the front
                                $newPhone = '+44' . '' . $phone;
                                $newPhone = preg_replace('/\s/', '', $newPhone);
                                break;
                            }
                    }
                }
                $leadTemp->setCustomerTel($newPhone);
                $em->persist($leadTemp);
                $em->flush();
            }
        }
        $counter = $counter + $limit;
        return $this->render('AppBundle:Confirm:confirmtemp.html.twig', array(
                    'counter' => $counter, 'name' => $name, 'form' => $form->createView(),
        ));
    }

    public function sentAction(Request $request, $page) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $namesearch = False;
        $emailsearch = False;

        $limit = 20;
        $offset = ($limit * $page) - $limit;

        $countAll = $em->getRepository('AppBundle:Confirmsent')
                ->countall();
        $confirmationSent = $em->getRepository('AppBundle:Confirmsent')
                ->findConfSentbylimit($limit, $offset);

        $pager = new Paginator($page, $countAll, $limit);

        //Get the template name with the ID
        $allTemplates = $em->getRepository('AppBundle:Ctemplate')
                ->findNames();
        foreach ($allTemplates as $cTemplate) {
            $allTemplate[$cTemplate['id']] = $cTemplate['tempname'];
        }
        $search = array();
        $formsearch = $this->createFormBuilder($search)
                ->add('name', 'text', array(
                    'label' => False,
                    'required' => false, //'attr' => array('class' => 'input-sm', 'placeholder' => 'search')
                ))
                ->add('search', 'submit')// array('attr' => array('class' => 'btn-success btn-sm')))
                ->getForm();
        $formsearch->handleRequest($request);
        if ($formsearch->isValid()) {

            $searchTerm = $formsearch["name"]->getData();
            $searchTerm = trim($searchTerm);
            return $this->redirect($this->generateUrl('confirm_sent_search', array(
                                'page' => 1, 'searchTerm' => $searchTerm,
            )));
        }

        return $this->render('AppBundle:Confirm:confirmsent.html.twig', array(
                    'allTemplate' => $allTemplate,
                    'emailsearch' => $emailsearch,
                    'namesearch' => $namesearch,
                    'formsearch' => $formsearch->createView(),
                    'name' => $name,
                    'confsent' => $confirmationSent,
                    'pager' => $pager,
                    'page' => $page,));
    }

    /*
     *  Display search result and search form
     */

    public function confirmsentSearchAction(Request $request, $page, $searchTerm) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        //Get the template name with the ID
        $allTemplates = $em->getRepository('AppBundle:Ctemplate')
                ->findNames();
        foreach ($allTemplates as $cTemplate) {
            $allTemplate[$cTemplate['id']] = $cTemplate['tempname'];
        }

        $search = [];
        $formSearch = $this->createFormBuilder($search)
                ->add('search', 'text')
                ->add('save', 'submit', array('label' => 'Search'))
                ->getForm();

        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $page = 1;
            $limit = 50;
            $offset = ($limit * $page) - $limit;

            $searchTerm = $formSearch["search"]->getData();
            $countAll = $em->getRepository('AppBundle:Confirmsent')
                    ->countAllsearchToNumberBody($searchTerm);
            $result = $em->getRepository('AppBundle:Confirmsent')
                    ->searchNameEmailBody($searchTerm, $offset, $limit);
            $pager = new Paginator($page, $countAll, $limit);

            return $this->render('AppBundle:Confirm:confsentsearchresult.html.twig', array(
                        'result' => $result,
                        'name' => $name,
                        'formSearch' => $formSearch->createView(),
                        'pager' => $pager,
                        'searchTerm' => $searchTerm,
                        'allTemplate' => $allTemplate,
            ));
        }

        $limit = 50;
        $offset = ($limit * $page) - $limit;
        $countAll = $em->getRepository('AppBundle:Confirmsent')
                ->countAllsearchToNumberBody($searchTerm);
        $result = $em->getRepository('AppBundle:Confirmsent')
                ->searchNameEmailBody($searchTerm, $offset, $limit);
        $pager = new Paginator($page, $countAll, $limit);

        return $this->render('AppBundle:Confirm:confsentsearchresult.html.twig', array(
                    'result' => $result,
                    'name' => $name,
                    'formSearch' => $formSearch->createView(),
                    'pager' => $pager,
                    'searchTerm' => $searchTerm,
                    'allTemplate' => $allTemplate,
        ));
    }

    public function seesentAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thissent = $em->getRepository('AppBundle:Confirmsent')
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
        $thisTemplate = $em->getRepository('AppBundle:Ctemplate')
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

    public function seetemplateAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thisTemplate = $em->getRepository('AppBundle:Ctemplate')
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
        return $this->render('AppBundle:Confirm:confirmSeeTemplate.html.twig', array(
                    'attachname3' => $attachname3, 'attachname2' => $attachname2, 'attachname1' => $attachname1, 'title' => $title, 'subject' => $subject, 'body' => $body,
        ));
    }

    public function doctorlistAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $templayenamearray = false;

        $dlist = $em->getRepository('AppBundle:Doctorlist')
                ->findAll();

        $alltemplate = $em->getRepository('AppBundle:Rtemplate')
                ->findAll();
        $templatearray[0] = '';
        foreach ($alltemplate as $alltemp) {
            $templatearray[$alltemp->getId()] = $alltemp->getTempname();
        }
        foreach ($alltemplate as $at) {
            $templayenamearray[$at->getId()] = $at->getTempname();
        }
        $doctorlist = new Doctorlist();
        $form = $this->createForm(new DoctorlistType($templatearray), $doctorlist);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($doctorlist);
            $em->flush();
            return $this->redirectToRoute('confirm_dlist');
        }

        return $this->render('AppBundle:Confirm:confirmdlist.html.twig', array('dlist' => $dlist,
                    'templayenamearray' => $templayenamearray, 'alltemplate' => $alltemplate, 'form' => $form->createView(), 'name' => $name,
        ));
    }

    public function doctoreditAction(Request $request, $id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $dlist = $em->getRepository('AppBundle:Doctorlist')
                ->findAll();

        $doctor = $em->getRepository('AppBundle:Doctorlist')
                ->find($id);
        $alltemplate = $em->getRepository('AppBundle:Rtemplate')
                ->findAll();
        $templatearray[0] = '';
        foreach ($alltemplate as $alltemp) {
            $templatearray[$alltemp->getId()] = $alltemp->getTempname();
        }

        $form = $this->createForm(new DoctorlistType($templatearray), $doctor);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($doctor);
            $em->flush();
            return $this->redirectToRoute('confirm_dlist');
        }

        return $this->render('AppBundle:Confirm:confirmdocedit.html.twig', array('dlist' => $dlist,
                    'form' => $form->createView(), 'name' => $name,
        ));
    }

    public function setdocactiveAction($id) {
        $em = $this->getDoctrine()->getManager();
        $dlist = $em->getRepository('AppBundle:Doctorlist')
                ->find($id);
        $active = $dlist->getActive();
        if ($active === 1) {
            $dlist->setActive(0);
            $em->flush();
        } else {
            $dlist->setActive(1);
            $em->flush();
        }
        return $this->redirectToRoute('confirm_dlist');
    }

    public function doctordeleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $dlist = $em->getRepository('AppBundle:Doctorlist')
                ->find($id);
        $em->remove($dlist);
        $em->flush();
        return $this->redirectToRoute('confirm_dlist');
    }

    public function ajaxtemplateAction($id) {
        $em = $this->getDoctrine()->getManager();

        $ctemplate = $em->getRepository('AppBundle:Ctemplate')
                ->find($id);
        $subject = $ctemplate->getSubject();

        $response = new Response(json_encode($subject));
        return $response;
    }

    public function ajaxattachAction($id) {
        $em = $this->getDoctrine()->getManager();
        $attname1 = false;
        $attname2 = false;
        $attname3 = false;

        $ctemplate = $em->getRepository('AppBundle:Ctemplate')
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

    public function ajaxtemplatebodyAction($id) {
        $em = $this->getDoctrine()->getManager();

        $ctemplate = $em->getRepository('AppBundle:Ctemplate')
                ->find($id);
        $body = $ctemplate->getBody();
        $response = new Response(json_encode($body));
        return $response;
    }

    public function templateAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $ctemplist = $em->getRepository('AppBundle:Ctemplate')->findAll();
        if (!$ctemplist) {
            $ctemplist = false;
        }
        $attachment = $em->getRepository('AppBundle:Product')
                ->findbylistname('attachment');
        $attachmentsarray = array();
        $attachmentsarray[] = '';
        foreach ($attachment as $temp) {
            $attachmentsarray[$temp->getId()] = $temp->getImageName();
        }
        $ctemplate = new Ctemplate();
        $form = $this->createFormBuilder($ctemplate)
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
            $ctemplate->setUpdatedAt();
            $ctemplate->setUsername($name);
            $em->persist($ctemplate);
            $em->flush();

            return $this->redirectToRoute('confirm_template');
        }
        return $this->render('AppBundle:Confirm:confirmtemplate.html.twig', array('ctemplist' => $ctemplist,
                    'form' => $form->createView(), 'name' => $name,
        ));
    }

    public function cTemplateEditAction(Request $request, $id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $oldattname1 = false;
        $oldattname2 = false;
        $oldattname3 = false;
        $ctemplist = $em->getRepository('AppBundle:Ctemplate')->find($id);
        if (!$ctemplist) {
            $ctemplist = false;
        }
        $attachment = $em->getRepository('AppBundle:Product')
                ->findbylistname('attachment');
        $attachmentsarray = array();
        $attachmentsarray[] = '';
        foreach ($attachment as $temp) {
            $attachmentsarray[$temp->getImageName()] = $temp->getImageName();
        }
        $oldtemp = $em->getRepository('AppBundle:Ctemplate')->find($id);
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

        $ctemplate = new Ctemplate();

        $form = $this->createFormBuilder($ctemplate)
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
            $template = $em->getRepository('AppBundle:Ctemplate')->find($id);
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

            return $this->redirectToRoute('confirm_template');
        }

        return $this->render('AppBundle:Confirm:confirmtempedit.html.twig', array('ctemplist' => $ctemplist,
                    'id' => $id, 'oldattname1' => $oldattname1, 'oldattname2' => $oldattname2, 'oldattname3' => $oldattname3, 'form' => $form->createView(), 'name' => $name,
        ));
    }

    public function removeattachmentAction($id, $attid) {
        $em = $this->getDoctrine()->getManager();
        $oldtemp = $em->getRepository('AppBundle:Ctemplate')->find($id);
        if ($attid === '1') {
            $oldtemp->setAttach1(0);
            $em->flush();
        }
        if ($attid === '2') {
            $oldtemp->setAttach2(0);
            $em->flush();
        }
        if ($attid === '3') {
            $oldtemp->setAttach3(0);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('confirm_templateedit', array('id' => $id)));
    }

    public function etemplateremoveattachAction($id, $attid) {
        $em = $this->getDoctrine()->getManager();
        $oldtemp = $em->getRepository('AppBundle:Etemplate')->find($id);
        if ($attid === '1') {
            $oldtemp->setAttach1(0);
            $em->flush();
        }
        if ($attid === '2') {
            $oldtemp->setAttach2(0);
            $em->flush();
        }
        if ($attid === '3') {
            $oldtemp->setAttach3(0);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('lead_template_edit', array('id' => $id)));
    }

    public function ctemplatedeleteAction($id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $etemplate = $em->getRepository('AppBundle:Ctemplate')->find($id);
        $em->remove($etemplate);
        $em->flush();

        return $this->redirect($this->generateUrl('confirm_template'));
    }

    public function ctemplateduplicateAction($id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $ctemplate = $em->getRepository('AppBundle:Ctemplate')->find($id);
        $newtemplate = new Ctemplate();
        $oldtempname = $ctemplate->getTempname();
        $lenold = strlen($oldtempname);
        $newtempname = substr_replace($oldtempname, ' - ' . $name, $lenold);
        $newtemplate->setTempname($newtempname);
        $newtemplate->setSubject($ctemplate->getSubject());
        $newtemplate->setBody($ctemplate->getBody());
        $newtemplate->setUsername($name);
        $newtemplate->setUpdatedAt();
        $em->persist($newtemplate);
        $em->flush();

        return $this->redirect($this->generateUrl('confirm_template'));
    }

    public function uploadAction(request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $uploads = $em->getRepository('AppBundle:Product')
                ->findbylistname('attachment');
        if (!$uploads) {
            $uploads = false;
        }


        $document = new Product();
        $form = $this->createFormBuilder($document)
                ->add('imageFile', 'file', array(
                    'label' => 'PDF file',))
                ->add('save', 'submit', array('label' => 'Upload'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/images/products/';
            $directoryPath = preg_replace("/app..../i", "", $directoryPath);
            $document->setUsername($name);
            $document->setListname('attachment');
            $document->setPath($directoryPath);
            $em->persist($document);
            $em->flush();
            return $this->redirect($this->generateUrl('confirmmanager_upload'));
        }
        return $this->render('AppBundle:Confirm:confirmupload.html.twig', array('name' => $name, 'uploads' => $uploads, 'form' => $form->createView()));
    }

    public function uploaddeleteAction($id) {

        $em = $this->getDoctrine()->getManager();
        $attid = $id;
        $attach1 = $em->getRepository('AppBundle:Ctemplate')->findatt1($attid);
        $attach2 = $em->getRepository('AppBundle:Ctemplate')->findatt2($attid);
        $attach3 = $em->getRepository('AppBundle:Ctemplate')->findatt3($attid);
        if ($attach1) {
            foreach ($attach1 as $att1) {
                $att1->setAttach1(0);
                $em->flush();
            }
        }
        if ($attach2) {
            foreach ($attach2 as $att2) {
                $att2->setAttach2(0);
                $em->flush();
            }
        }
        if ($attach3) {
            foreach ($attach3 as $att3) {
                $att3->setAttach3(0);
                $em->flush();
            }
        }
        $upload = $em->getRepository('AppBundle:Product')->find($id);
        $em->remove($upload);
        $em->flush();

        return $this->redirect($this->generateUrl('confirmmanager_upload'));
    }

    public function etemplateAction(request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $etemplist = $em->getRepository('AppBundle:Etemplate')->findAll();
        if (!$etemplist) {
            $etemplist = false;
        }

        $attachment = $em->getRepository('AppBundle:Product')
                ->findbylistname('attachment');
        $attachmentsarray = array();
        $attachmentsarray[] = '';
        foreach ($attachment as $temp) {
            $attachmentsarray[$temp->getId()] = $temp->getImageName();
        }

        $etemplate = new Etemplate($attachmentsarray);
        $form = $this->createForm(new EtemplateType($attachmentsarray), $etemplate);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $etemplate->setUpdatedAt();
            $etemplate->setUsername($name);
            $em->persist($etemplate);
            $em->flush();

            return $this->redirectToRoute('lead_template');
        }
        return $this->render('AppBundle:Confirm:leadtemplate.html.twig', array('etemplist' => $etemplist,
                    'form' => $form->createView(), 'name' => $name,
        ));
    }

    public function seeleadtemplateAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thisTemplate = $em->getRepository('AppBundle:Etemplate')
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
        if ($att2id) {
            $attach3 = $em->getRepository('AppBundle:Product')->find($att3id);
            if ($attach3) {
                $attachname3 = $attach3->getImageName();
            }
        }
        return $this->render('AppBundle:Confirm:confirmSeeTemplate.html.twig', array(
                    'attachname3' => $attachname3, 'attachname2' => $attachname2, 'attachname1' => $attachname1, 'title' => $title, 'subject' => $subject, 'body' => $body,
        ));
    }

    public function etemplateeditAction(Request $request, $id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $oldattname1 = false;
        $oldattname2 = false;
        $oldattname3 = false;

        $etemplist = $em->getRepository('AppBundle:Etemplate')->findAll();
        if (!$etemplist) {
            $etemplist = false;
        }
        $attachment = $em->getRepository('AppBundle:Product')
                ->findbylistname('attachment');
        $attachmentsarray = array();
        $attachmentsarray[] = '';
        foreach ($attachment as $temp) {
            $attachmentsarray[$temp->getImageName()] = $temp->getImageName();
        }

        $oldtemp = $em->getRepository('AppBundle:Etemplate')->find($id);
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

        $etemplate = new Etemplate();

        $form = $this->createFormBuilder($etemplate)
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
            $template = $em->getRepository('AppBundle:Etemplate')->find($id);
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

            return $this->redirectToRoute('lead_template');
        }
        return $this->render('AppBundle:Confirm:leadtemplateedit.html.twig', array('etemplist' => $etemplist,
                    'id' => $id, 'form' => $form->createView(), 'name' => $name, 'oldattname1' => $oldattname1, 'oldattname2' => $oldattname2, 'oldattname3' => $oldattname3,
        ));
    }

    public function etemplatedeleteAction($id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('confirm_texttemplate'));
        }


        $etemplate = $em->getRepository('AppBundle:Etemplate')->find($id);
        $em->remove($etemplate);
        $em->flush();

        return $this->redirect($this->generateUrl('lead_template'));
    }

    public function etemplateduplicateAction($id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $etemplate = $em->getRepository('AppBundle:Etemplate')->find($id);
        $newtemplate = new Etemplate();
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

        return $this->redirect($this->generateUrl('lead_template'));
    }

    /*
     *  Send text message and list all sent messages with paginator
     */

    public function textmessageAction(Request $request, $page) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        
        $textMessage = [];
        $limit = 20;
        $offset = ($limit * $page) - $limit;

        $countAll = $em->getRepository('AppBundle:Textmessage')
                ->countAllSent();
        $sentText = $em->getRepository('AppBundle:Textmessage')
                ->findSent($limit, $offset);

        $pager = new Paginator($page, $countAll, $limit);

        //search form
        $search = [];
        $formSearch = $this->createFormBuilder($search)
                ->add('search', 'text')
                ->add('save', 'submit', array('label' => 'Search'))
                ->getForm();

        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $searchTerm = $formSearch["search"]->getData();
            return $this->redirect($this->generateUrl('confirm_sent_sms_search', array(
                                'page' => 1, 'searchTerm' => $searchTerm,
            )));
        }

        $textMessage = [];
        $form = $this->createFormBuilder($textMessage)
                ->add('Phone', 'text', array(
                    'label' => 'Phone',
                    'required' => true,
                    'attr' => array('placeholder' => '+44.......')
                ))
                ->add('Message', 'textarea')
                ->add('save', 'submit', array('label' => 'Send message'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $parameterPath = $this->container->getParameter('kernel.root_dir') . '/config/twilioParameters.yml';
            $value = Yaml::parse(file_get_contents($parameterPath));
            $sid = $value['parameters']['sid'];
            $secret = $value['parameters']['secret'];
            $accountSid = $value['parameters']['accountSid'];
        
            $client = new Client($sid, $secret, $accountSid);
            $body = $form["Message"]->getData();
            $toNumber = $form["Phone"]->getData();
            $fromNumber = '+447903576021';

            $client->messages->create(
                    $toNumber, array(
                'from' => $fromNumber,
                'body' => $body,
                    )
            );

            //check existing lead
            $checkNumber = substr($toNumber, 3);
            $thisLead = $em->getRepository('AppBundle:Lead')
                    ->checkPhoneNumberTextMessage($checkNumber);

            //if sent is success save to the database
            $textMessage = new Textmessage();
            $textMessage->setCreatedAt(new \DateTime());
            $textMessage->setBody($body);
            $textMessage->setFromNumber($fromNumber);
            $textMessage->setToNumber($toNumber);
            $textMessage->setMessageType(0);
            if ($thisLead) {
                $textMessage->setLeadId($thisLead[0]['id']);
                $textMessage->setClientName($thisLead[0]['customerName']);
            }
            $em->persist($textMessage);
            $em->flush();

            return $this->redirect($this->generateUrl('confirm_sms'));
        }

        return $this->render('AppBundle:Confirm:textmessage.html.twig', array(
                    'pager' => $pager, 'name' => $name, 'form' => $form->createView(), 'sentText' => $sentText, 'formSearch' => $formSearch->createView(),
        ));
    }

    /*
     * * Display received text messages
     */

    public function textMessageReceivedAction(Request $request, $page) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $limit = 20;
        $offset = ($limit * $page) - $limit;

        $countAll = $em->getRepository('AppBundle:Textmessage')
                ->countAllReceived();
        $receivedText = $em->getRepository('AppBundle:Textmessage')
                ->findReceived($limit, $offset);

        $pager = new Paginator($page, $countAll, $limit);

        //search form
        $search = [];
        $formSearch = $this->createFormBuilder($search)
                ->add('search', 'text')
                ->add('save', 'submit', array('label' => 'Search'))
                ->getForm();

        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $searchTerm = $formSearch["search"]->getData();
            return $this->redirect($this->generateUrl('confirm_received_sms_search', array(
                                'page' => 1, 'searchTerm' => $searchTerm,
            )));
        }

        return $this->render('AppBundle:Confirm:textmessagereceived.html.twig', array(
                    'pager' => $pager, 'name' => $name, 'receivedText' => $receivedText, 'formSearch' => $formSearch->createView(),
        ));
    }

    /*
     * * Display received text messages
     */

    public function textMessageReceivedSearchAction(Request $request, $page, $searchTerm) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $search = [];
        $formSearch = $this->createFormBuilder($search)
                ->add('search', 'text')
                ->add('save', 'submit', array('label' => 'Search'))
                ->getForm();

        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $page = 1;
            $limit = 50;
            $offset = ($limit * $page) - $limit;

            $searchTerm = $formSearch["search"]->getData();
            $countAll = $em->getRepository('AppBundle:Textmessage')
                    ->countAllsearchToNumberBody($searchTerm);
            $result = $em->getRepository('AppBundle:Textmessage')
                    ->searchToNumberBody($searchTerm, $offset, $limit);
            $pager = new Paginator($page, $countAll, $limit);

            return $this->render('AppBundle:Confirm:textmessagereceived-search.html.twig', array(
                        'result' => $result,
                        'name' => $name,
                        'formSearch' => $formSearch->createView(),
                        'pager' => $pager,
                        'searchTerm' => $searchTerm,
            ));
        }

        $limit = 50;
        $offset = ($limit * $page) - $limit;
        $countAll = $em->getRepository('AppBundle:Textmessage')
                ->countAllsearchToNumberBody($searchTerm);
        $result = $em->getRepository('AppBundle:Textmessage')
                ->searchToNumberBody($searchTerm, $offset, $limit);
        $pager = new Paginator($page, $countAll, $limit);

        return $this->render('AppBundle:Confirm:textmessagereceived-search.html.twig', array(
                    'result' => $result,
                    'name' => $name,
                    'formSearch' => $formSearch->createView(),
                    'pager' => $pager,
                    'searchTerm' => $searchTerm,
        ));
    }

    /*
     * * Display search sent text messages
     */

    public function textMessageSentSearchAction(Request $request, $page, $searchTerm) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $search = [];
        $formSearch = $this->createFormBuilder($search)
                ->add('search', 'text')
                ->add('save', 'submit', array('label' => 'Search'))
                ->getForm();

        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $page = 1;
            $limit = 5;
            $offset = ($limit * $page) - $limit;

            $searchTerm = $formSearch["search"]->getData();
            $countAll = $em->getRepository('AppBundle:Textmessage')
                    ->countAllsearchSentToNumberBody($searchTerm);
            $result = $em->getRepository('AppBundle:Textmessage')
                    ->searchSentToNumberBody($searchTerm, $offset, $limit);
            $pager = new Paginator($page, $countAll, $limit);

            return $this->render('AppBundle:Confirm:textmessagesent-search.html.twig', array(
                        'result' => $result,
                        'name' => $name,
                        'formSearch' => $formSearch->createView(),
                        'pager' => $pager,
                        'searchTerm' => $searchTerm,
            ));
        }

        $limit = 5;
        $offset = ($limit * $page) - $limit;
        $countAll = $em->getRepository('AppBundle:Textmessage')
                ->countAllsearchSentToNumberBody($searchTerm);
        $result = $em->getRepository('AppBundle:Textmessage')
                ->searchSentToNumberBody($searchTerm, $offset, $limit);
        $pager = new Paginator($page, $countAll, $limit);

        return $this->render('AppBundle:Confirm:textmessagesent-search.html.twig', array(
                    'result' => $result,
                    'name' => $name,
                    'formSearch' => $formSearch->createView(),
                    'pager' => $pager,
                    'searchTerm' => $searchTerm,
        ));
    }

    /*
     * RECEIVE POST REQUEST FROM TWILIO API SERVER WHEN A TEXT MESSAGE RECEIVED
     */

    public function receiveTextMessageAction(Request $request) {

        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $textBody = $this->get('request')->request->get('Body');
            $fromNumber = $this->get('request')->request->get('From');
            $accountSid = $this->get('request')->request->get('AccountSid');

            //check existing lead
            $checkNumber = substr($fromNumber, 3);
            $thisLead = $em->getRepository('AppBundle:Lead')
                    ->checkPhoneNumberTextMessage($checkNumber);

            $textMessage = new Textmessage();
            $textMessage->setCreatedAt(new \DateTime());
            $textMessage->setBody($textBody);
            $textMessage->setFromNumber($fromNumber);
            $textMessage->setToNumber('+447903576021');
            if ($thisLead) {
                $textMessage->setLeadId($thisLead[0]['id']);
                $textMessage->setClientName($thisLead[0]['customerName']);
            }
            $textMessage->setMessageType(1);
            $em->persist($textMessage);
            $em->flush();

            $xml = '<Response></Response>';
            $response = new Response($xml);
            $response->headers->set('Content-Type', 'text/xml');

            return $response;
        } else {
            throw new AccessDeniedHttpException('You are not allow to visit this page!');
        }
    }

    /*
     * Create/Edit/list all text-message template
     * 
     */

    public function texttemplateAction(Request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $textTemplateList = $em->getRepository('AppBundle:Texttemplate')->findAll();
        if (!$textTemplateList) {
            $textTemplateList = false;
        }

        $template = new Texttemplate();

        $form = $this->createFormBuilder($template)
                ->add('templateName', 'text')
                ->add('textBody', 'textarea')
                ->add('save', 'submit', array('label' => 'Save'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $template->setCreatedAt(new \DateTime());
            $template->setUsername($name);
            $em->persist($template);
            $em->flush();

            return $this->redirect($this->generateUrl('confirm_texttemplate'));
        }


        return $this->render('AppBundle:Confirm:texttemplate.html.twig', array(
                    'name' => $name, 'textTemplateList' => $textTemplateList, 'form' => $form->createView(),
        ));
    }

    /*
     * Edit text-message template
     * 
     */

    public function texttemplateeditAction(Request $request, $id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $thisTemplate = $em->getRepository('AppBundle:Texttemplate')->find($id);
        if (!$thisTemplate) {
            return $this->redirect($this->generateUrl('confirm_texttemplate'));
        }

        $form = $this->createFormBuilder($thisTemplate)
                ->add('templateName', 'text')
                ->add('textBody', 'textarea')
                ->add('save', 'submit', array('label' => 'Save'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thisTemplate->setUsername($name);
            $em->persist($thisTemplate);
            $em->flush();

            return $this->redirect($this->generateUrl('confirm_texttemplate'));
        }

        return $this->render('AppBundle:Confirm:textTemplateEdit.html.twig', array(
                    'name' => $name, 'form' => $form->createView(),
        ));
    }

    /*
     * Delete text-message template
     * 
     */

    public function texttemplatedeleteAction($id) {

        $em = $this->getDoctrine()->getManager();
        $thisTemplate = $em->getRepository('AppBundle:Texttemplate')->find($id);
        if (!$thisTemplate) {
            return $this->redirect($this->generateUrl('confirm_texttemplate'));
        }

        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('confirm_texttemplate'));
        }
        $em->remove($thisTemplate);
        $em->flush();

        return $this->redirect($this->generateUrl('confirm_texttemplate'));
    }

}
