<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PhpImap\Mailbox;
use AppBundle\Entity\Inbox;
use AppBundle\Entity\Lead;
use AppBundle\Entity\Settings;
use AppBundle\Entity\Etemplate;
use AppBundle\Entity\Elist;
use AppBundle\Entity\Product;
use AppBundle\Form\SettingsType;
use AppBundle\Form\SettingssmtpType;
use AppBundle\Form\EtemplateType;

class EmailController extends Controller {

    public function emailAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();


        return $this->render('AppBundle:Default:emailbase.html.twig', array('name' => $name,));
    }

    public function settingsAction(request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $settlist = $em->getRepository('AppBundle:Settings')->findAll();
        $settings = new Settings();
        $form = $this->createForm(new SettingsType(), $settings);

        $form->handleRequest($request);

        if ($form->isValid()) {

            //find if there is any active settings already
            //persit data to database
            $settings->setActive('1');
            $settings->setIncoming('1');
            $settings->setCreatedAt();
            $settings->setUsername($name);
            $em->persist($settings);
            $em->flush();

            return $this->redirectToRoute('emailmanager_settings');
        }

        $form2 = $this->createForm(new SettingssmtpType(), $settings);
        $form2->handleRequest($request);

        if ($form2->isValid()) {
            //find if there is any active settings already
            $em = $this->getDoctrine()->getManager();


            //persit data to database
            $settings->setCreatedAt();
            $settings->setActive('1');
            $settings->setIncoming('1');
            $settings->setUsername($name);
            $em = $this->getDoctrine()->getManager();
            $em->persist($settings);
            $em->flush();

            return $this->redirectToRoute('emailmanager_settings');
        }

        return $this->render('AppBundle:Default:settings.html.twig', array(
                    'form' => $form->createView(), 'form2' => $form2->createView(), 'name' => $name, 'settlist' => $settlist,
        ));
    }

    public function maintenanceAction() {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $countinbox = $em->getRepository('AppBundle:Inbox')->countAll();

        $startdate = new \DateTime();
        date_modify($startdate, '-30 day');
        $oldinbox = $em->getRepository('AppBundle:Inbox')->findolderOneMonth($startdate);
        $countold = count($oldinbox);

        return $this->render('AppBundle:Default:emailmaintenance.html.twig', array('name' => $name, 'countold' => $countold, 'countinbox' => $countinbox));
    }

    public function emailcleaninboxAction() {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $startdate = new \DateTime();
        date_modify($startdate, '-30 day');

        $oldinbox = $em->getRepository('AppBundle:Inbox')->findolderOneMonth($startdate);

        foreach ($oldinbox as $oldin) {
            $em->remove($oldin);
        }
        $em->flush();


        return $this->redirect($this->generateUrl('emailmanager_maintenance'));
    }

    public function deletesettingsAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Settings')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('emailmanager_settings'));
    }

    public function activatesettingsAction($id) {
        $login = $this->getUser();
        $username = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $settnew = $em->getRepository('AppBundle:Settings')->find($id);
        $oldsett = $settnew->getActive();
        if ($oldsett == '0') {
            $settnew->setActive('1');
        }
        if ($oldsett == '1') {
            $settnew->setActive('0');
        }

        $em->flush();

        return $this->redirect($this->generateUrl('emailmanager_settings'));
    }

    public function activateincomingAction($id) {
        $login = $this->getUser();
        $username = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $settnew = $em->getRepository('AppBundle:Settings')->find($id);
        $oldsett = $settnew->getIncoming();
        if ($oldsett == '0' || $oldsett === 0) {
            $settnew->setIncoming('1');
        }
        if ($oldsett == '1') {
            $settnew->setIncoming('0');
        }

        $em->flush();

        return $this->redirect($this->generateUrl('emailmanager_settings'));
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

            return $this->redirectToRoute('emailmanager_etemplate');
        }
        return $this->render('AppBundle:Default:etemplateedit.html.twig', array('ctemplist' => $ctemplist,
                    'id' => $id, 'oldattname1' => $oldattname1, 'oldattname2' => $oldattname2, 'oldattname3' => $oldattname3, 'form' => $form->createView(), 'name' => $name,
        ));
    }

    public function etemplatedeleteAction($id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $etemplate = $em->getRepository('AppBundle:Etemplate')->find($id);
        $em->remove($etemplate);
        $em->flush();

        return $this->redirect($this->generateUrl('emailmanager_etemplate'));
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

        return $this->redirect($this->generateUrl('emailmanager_etemplate'));
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
            $uname = $this->getUser();
            $document->setUsername($uname);
            $document->setListname('attachment');
            $document->setPath($directoryPath);
            $em->persist($document);
            $em->flush();
            return $this->redirect($this->generateUrl('emailmanager_upload'));
        }
        return $this->render('AppBundle:Default:emailupload.html.twig', array('name' => $name, 'uploads' => $uploads, 'form' => $form->createView()));
    }

    public function uploaddeleteAction($id, $redirect) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $upload = $em->getRepository('AppBundle:Product')->find($id);
        $em->remove($upload);
        $em->flush();
        if ($redirect === 'home') {
            return $this->redirect($this->generateUrl('emailmanager_upload'));
        }
        if ($redirect === 'marketing') {
            return $this->redirect($this->generateUrl('marketing_campaign'));
        }
    }

    public function emailonlineAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $allmail = $em->getRepository('AppBundle:Inbox')->findAll();

        return $this->render('AppBundle:Default:email.html.twig', array('allmail' => $allmail, 'name' => $name,));
    }

    public function checkmailAction() {
        $em = $this->getDoctrine()->getManager();
        $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/Files/emails/';
        $directoryPath = preg_replace("/app..../i", "", $directoryPath);

        $thisSettings = $em->getRepository('AppBundle:Settings')->findOneBy(array('eusername' => 'crm.testmailer@gmail.com'));
        $imapServer = $thisSettings->getImapserver();
        $imapPort = $thisSettings->getImapport();
        $esuername = $thisSettings->getEusername();
        $epassword = $thisSettings->getEpassword();
        $lastemail = $thisSettings->getLastemail();
        
        $connectionString = '{' . $imapServer . ':' . $imapPort . '}INBOX';
        $mailbox = new Mailbox($connectionString, $esuername, $epassword, $directoryPath);
        $mailsIds = $mailbox->searchMailBox('ALL');
        if (!$mailsIds) {
            return $this->redirect($this->generateUrl('lead_manager'));
        }
        $mailId = '';
        if (!$lastemail) {
            $firstKey = 0;
        } else {
            $firstKey = array_search($lastemail, $mailsIds) + 1;
        }

        $slicedMailsIds = array_slice($mailsIds, $firstKey);

        foreach ($slicedMailsIds as $mid) {
            $em = $this->getDoctrine()->getManager();

            $mailId = (int) $mid; //reset($mailsIds);
            $mail = $mailbox->getMail($mailId);
            $emailid = $mail->messageId;
            if ($emailid) {
                $inbox = new Inbox();
                $textPlain = $mail->textPlain;
                if (!$textPlain) {
                    $textHtml = $mail->textHtml;
                    if ($textHtml) {
                        $textHtmlcleaned = strip_tags($textHtml);
                        $textHtmlnoemptylines = preg_replace('/^\n+|^[\t\s]*\n+/m', '', $textHtmlcleaned);
                        $textPlain = $textHtmlnoemptylines;
                    }
                }

                if ($textPlain) {
                    $inbox->setContent($textPlain);
                    $inbox->setMailid($mailId);

                    $fromemail = $mail->fromAddress;
                    $inbox->setFromemail($fromemail);

                    $emailsubject = $mail->subject;
                    $inbox->setSubject($emailsubject);

                    $emaildate = $mail->date;
                    $inbox->setMaildate($emaildate);

                    $inbox->setCreatedAt();
                    $inbox->setStatus('new');
                    //find out the type of the incoming email. If its a lead the source will be defined.
                    if (strpos($emailsubject, 'A callback message received') !== false) {
                        $source = 'Dent1st callback';
                        $inbox->setSource($source);
                        $em->persist($inbox);
                        $em->flush();
                        $checklead = $this->checkleadCallback($textPlain, $source, $mailId);
                    }

                    if (strpos($emailsubject, 'There is message from the callback form!') !== false) {
                        $source = 'HSDIC';
                        $inbox->setSource($source);
                        $em->persist($inbox);
                        $em->flush();
                        $checklead = $this->checkleadHSDIC($textPlain, $source, $mailId);
                    }

                    if (strpos($emailsubject, 'A new treatment demand received') !== false) {
                        $source = 'Dent1st treatment';
                        $inbox->setSource($source);
                        $em->persist($inbox);
                        $em->flush();
                        $checklead = $this->checkleadTreatment($textPlain, $source, $mailId);
                    }

                    if (strpos($emailsubject, 'There is message from the contact form!') !== false) {
                        $source = 'HSDIC';
                        $inbox->setSource($source);
                        $em->persist($inbox);
                        $em->flush();
                        $checklead = $this->checkleadHSDICcontact($textPlain, $source, $mailId);
                    }
                }
            }
            $thisSettings->setLastemail($mailId);
            $thisSettings->setLastdownload(new \DateTime());
            $em->persist($thisSettings);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('lead_manager'));
    }

    public function checkleadCallback($textPlain, $source, $mailId) {

        $leadcontact = $this->findcontact($textPlain);
        $em = $this->getDoctrine()->getManager();
        //check if the lead already exist in the database is OFF
        //$leadcheck = $em->getRepository('AppBundle:Lead')->findBy(array('customerEmail' => $leadcontact[2]));
        //if (!$leadcheck) {
        $lead = new Lead();
        // split name to first and surname
        $leadcontact[0] = rtrim(preg_replace('/[^A-Za-z0-9\. -]/', '', $leadcontact[0]));
        $splitnamearray = $this->namesplit($leadcontact[0]);
        $lead->setSurname($splitnamearray[1]);
        $lead->setFirstname($splitnamearray[0]);
        // rest of the persist
        $lead->setCustomerName($leadcontact[0]);
        //modify phone
        $newPhone = $this->modifyPhone($leadcontact[1]);
        $lead->setCustomerTel($newPhone);
        $lead->setCustomerEmail($leadcontact[2]);
        $lead->setContacted(0);
        $lead->setCreatedAt(new \DateTime());
        $lead->setSource($source);
        $lead->setStatus('new');
        $lead->setEmailid($mailId);
        $lead->setMessage($leadcontact[3]);
        $em->persist($lead);
        $em->flush();

        return $this->redirect($this->generateUrl('lead_manager'));
    }

    public function checkleadHSDIC($textPlain, $source, $mailId) {

        $leadcontact = $this->findcontactHSDIC($textPlain);
        $em = $this->getDoctrine()->getManager();
        //$leadcheck = $em->getRepository('AppBundle:Lead')->findBy(array('customerEmail' => $leadcontact[2]));
        //if (!$leadcheck) {
        $lead = new Lead();
        // split name to first and surname
        $leadcontact[0] = rtrim(preg_replace('/[^A-Za-z0-9\. -]/', '', $leadcontact[0]));
        $splitnamearray = $this->namesplit($leadcontact[0]);
        $lead->setSurname($splitnamearray[1]);
        $lead->setFirstname($splitnamearray[0]);
        // rest of the persist
        $lead->setCustomerName($leadcontact[0]);
        //modify phone
        $newPhone = $this->modifyPhone($leadcontact[1]);
        $lead->setCustomerTel($newPhone);
        $lead->setCustomerEmail($leadcontact[2]);
        $lead->setContacted(0);
        $lead->setCreatedAt(new \DateTime());
        $lead->setSource($source);
        $lead->setStatus('new');
        $lead->setEmailid($mailId);
        $lead->setMessage($leadcontact[3]);
        $em->persist($lead);
        $em->flush();

        return $this->redirect($this->generateUrl('lead_manager'));
    }

    public function checkleadHSDICcontact($textPlain, $source, $mailId) {

        $leadcontact = $this->findcontactHSDICcontact($textPlain);
        $em = $this->getDoctrine()->getManager();
        //$leadcheck = $em->getRepository('AppBundle:Lead')->findBy(array('customerEmail' => $leadcontact[2]));
        //if (!$leadcheck) {
        $lead = new Lead();
        // split name to first and surname
        $leadcontact[0] = rtrim(preg_replace('/[^A-Za-z0-9\. -]/', '', $leadcontact[0]));
        $splitnamearray = $this->namesplit($leadcontact[0]);
        $lead->setSurname($splitnamearray[1]);
        $lead->setFirstname($splitnamearray[0]);
        // rest of the persist
        $lead->setCustomerName($leadcontact[0]);
        //modify phone
        $newPhone = $this->modifyPhone($leadcontact[1]);
        $lead->setCustomerTel($newPhone);
        $lead->setCustomerEmail($leadcontact[2]);
        $lead->setCreatedAt(new \DateTime());
        $lead->setContacted(0);
        $lead->setSource($source);
        $lead->setStatus('new');
        $lead->setEmailid($mailId);
        $lead->setMessage($leadcontact[3]);
        $em->persist($lead);
        $em->flush();

        return $this->redirect($this->generateUrl('lead_manager'));
    }

    public function checkleadTreatment($textPlain, $source, $mailId) {

        $leadcontact = $this->findcontactTreatment($textPlain);
        $em = $this->getDoctrine()->getManager();
        //$leadcheck = $em->getRepository('AppBundle:Lead')->findBy(array('customerEmail' => $leadcontact[2]));
        //if (!$leadcheck) {
        $lead = new Lead();
        // split name to first and surname
        $leadcontact[0] = rtrim(preg_replace('/[^A-Za-z0-9\. -]/', '', $leadcontact[0]));
        $splitnamearray = $this->namesplit($leadcontact[0]);
        $lead->setSurname($splitnamearray[1]);
        $lead->setFirstname($splitnamearray[0]);
        // rest of the persist
        $lead->setCustomerName($leadcontact[0]);
        //modify phone
        $newPhone = $this->modifyPhone($leadcontact[1]);
        $lead->setCustomerTel($newPhone);
        $lead->setCustomerEmail($leadcontact[2]);
        $lead->setContacted(0);
        $lead->setCreatedAt(new \DateTime());
        $lead->setSource($source);
        $lead->setStatus('new');
        $lead->setEmailid($mailId);
        $lead->setMessage($leadcontact[3]);
        $em->persist($lead);
        $em->flush();

        return $this->redirect($this->generateUrl('lead_manager'));
    }

    public function findcontact($textplain) {
        $a = $textplain;
        $contact = array();
        // find a name in the email
        $n = strstr($a, "Name:");
        $n1pos = strpos($n, " ") + 1;
        $n2pos = strpos($n, "E-mail:");
        $name = substr($n, $n1pos, $n2pos - $n1pos);
        //echo '<p>'.$name.'</p>';
        $contact[] = $name;

        // find a Telephone in the email
        $t1 = strstr($a, "Telephone:");
        $t2 = strstr($t1, "Message:", true);

        $t1pos = strpos($t1, ":") + 1;
        $t3 = substr($t2, $t1pos);
        $t4 = str_replace(' ', '', $t3);
        $tel = str_replace('-', '', $t4);
        $cleantel = trim($tel);
        $contact[] = $cleantel;

        // find a emailaddress in the textplain
        $e1 = strstr($a, "E-mail:");
        $e2 = strstr($e1, "Telephone:", true);

        $e1pos = strpos($e1, ":") + 1;
        $e3 = substr($e2, $e1pos);
        $e4 = str_replace(' ', '', $e3);
        $email = str_replace('-', '', $e4);
        $trimmedemail = strtolower(trim($email));
        $cleanedemail = str_replace(array("\r", "\n"), '', $trimmedemail);
        $contact[] = $cleanedemail;

        $m1 = strstr($a, "Message:");
        $m2pos = strpos($t1, "This is an automatic message, do not reply it!") - 46;
        $m3 = substr($m1, '0', $m2pos);
        $message = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $m3);
        $contact[] = $message;

        return $contact;
    }

    public function findcontactHSDIC($textplain) {
        $a = $textplain;
        $contact = array();

        //name
        $n = strstr($a, "Name");
        $n1pos = strpos($n, "Name") + 5;
        $n2pos = strpos($n, "E-mail");
        $nameraw = substr($n, $n1pos, $n2pos - $n1pos);
        $name = ltrim(rtrim($nameraw));
        $contact[] = $name;


        // find a Telephone in the email
        $t = strstr($a, "Phone");
        $t1pos = strpos($t, "Phone") + 6;
        $t2pos = strpos($t, "Message");
        $telraw = substr($t, $t1pos, $t2pos - $t1pos);
        $tel = ltrim(rtrim($telraw));
        $contact[] = $tel;

        // email new
        $e = strstr($a, "E-mail");
        $e1pos = strpos($e, "E-mail") + 6;
        $e2pos = strpos($e, "Phone");
        $emailraw = substr($e, $e1pos, $e2pos - $e1pos);
        $email = ltrim(rtrim($emailraw));
        $trimmedemail = strtolower(trim($email));
        $cleanedemail = str_replace(array("\r", "\n"), '', $trimmedemail);
        $contact[] = $cleanedemail;

        // find the message
        $m1 = strstr($t, "Message");
        $m2pos = strpos($t, "This message sent automatically, please do not reply!") - 56;
        $m3 = substr($m1, '8', $m2pos);
        $messageraw = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $m3);
        $message = ltrim(rtrim($messageraw));
        $contact[] = $message;
        return $contact;
    }

    public function findcontactHSDICcontact($textplain) {
        $a = $textplain;
        $contact = array();

        // find a name in the email
        $n = strstr($a, "Full Name:");
        $n1pos = strpos($n, " ") + 6;
        $n2pos = strpos($n, "Groupon Security Code:");
        $name = substr($n, $n1pos, $n2pos - $n1pos);
        if (strpos($name, 'Male') !== false) {
            $name = trim(strstr($name, 'Male', true));
        }
        if (strpos($name, 'Female') !== false) {
            $name = trim(strstr($name, 'Female', true));
        }
        $contact[] = $name;

        // find a Telephone in the email
        $t1 = strstr($a, "Phone:");
        $t2 = strstr($t1, "Address:", true);

        $t1pos = strpos($t1, ":") + 2;
        $t3 = substr($t2, $t1pos);
        $t4 = str_replace(' ', '', $t3);
        $tel = str_replace('-', '', $t4);
        $cleantel = trim($tel);
        $contact[] = $cleantel;

        // find any email address except own
        $e = strstr($a, "E-mail Address:");
        $e1pos = strpos($e, "E-mail Address:") + 16;
        $e2pos = strpos($e, "Phone:");
        $email = substr($e, $e1pos, $e2pos - $e1pos);
        $trimmedemail = strtolower(trim($email));
        $cleanedemail = str_replace(array("\r", "\n"), '', $trimmedemail);
        $contact[] = $cleanedemail;


        // find the message
        $m1 = strstr($t1, "Problem:");
        $m2pos = strpos($t1, "This message sent automatically, please do not reply!") - 67;
        $m3 = substr($m1, '8', $m2pos);
        $message = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $m3);
        $contact[] = $message;

        return $contact;
    }

    public function findcontactTreatment($textplain) {
        $a = $textplain;
        $contact = array();
        // find a name in the email
        $n = strstr($a, "Name:");
        $n1pos = strpos($n, " ") + 1;
        $n2pos = strpos($n, "E-mail:");
        $name = substr($n, $n1pos, $n2pos - $n1pos);
        $contact[] = $name;

        // find a Telephone in the email
        $t1 = strstr($a, "Daytime phone number:");
        $t2 = strstr($t1, "Evening phone number:", true);

        $t1pos = strpos($t1, ":") + 2;
        $t3 = substr($t2, $t1pos);
        $t4 = str_replace(' ', '', $t3);
        $tel = str_replace('-', '', $t4);
        $cleantel = trim($tel);
        $contact[] = $cleantel;

        //email new

        $e1 = strstr($a, "E-mail:");
        $e2 = strstr($e1, "Daytime phone number:", true);
        $e1pos = strpos($e1, ":") + 2;
        $e3 = substr($e2, $e1pos);
        $e4 = str_replace(' ', '', $e3);
        $email = str_replace('-', '', $e4);
        $trimmedemail = strtolower(trim($email));
        $cleanedemail = str_replace(array("\r", "\n"), '', $trimmedemail);
        $contact[] = $cleanedemail;

        // message

        $m1 = strstr($a, "Treatment needed:");
        $m2 = strstr($m1, "Regular datas", true);
        $m1pos = strpos($m1, ":") + 2;
        $message = substr($m2, $m1pos);
        $contact[] = $message;

        return $contact;
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

    public function modifyPhone($phone) {
        if ($phone !== false) {

            $phone = preg_replace('/\s/', '', $phone);
            //$phone = preg_replace('/\\\\u[0-9A-F]{4}/i', '', $phone);
            $phone = str_replace(' ', '', $phone);
            $phone = strip_tags($phone);
            
            if (strpos($phone, '+44') !== false) {
                $position44 =  strpos($phone, '+44');
                $phone = substr($phone, $position44);
            }


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
        } else {
            return true;
        }
        return $newPhone;
    }

}
