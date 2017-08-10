<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PhpImap\Mailbox;
use AppBundle\Entity\Inbox;
use AppBundle\Entity\Lead;
use AppBundle\Entity\Settings;
use AppBundle\Entity\Maininbox;
use AppBundle\Paginator\Paginator;
use AppBundle\Model\MaininboxSearch;
use AppBundle\SearchRepository\MaininboxRepository;
use AppBundle\Form\MaininboxSearchType;
use AppBundle\Entity\Mainsent;
use AppBundle\Entity\Mainstar;
use AppBundle\Entity\Mainfolder;
use AppBundle\Entity\Maincontact;
use AppBundle\Entity\Mainfilter;
use AppBundle\Entity\Mainmessagerules;
use AppBundle\Entity\Mainattachment;
use AppBundle\Entity\Signature;
use AppBundle\Entity\Etemplate;
use AppBundle\Entity\Elist;
use AppBundle\Entity\Product;
use AppBundle\Form\SettingsType;
use AppBundle\Form\SettingssmtpType;
use AppBundle\Form\EtemplateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainemailController extends Controller {

    public function indexAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $settlist = $em->getRepository('AppBundle:Settings')->findallactiveIncoming('1');
        $settMain = false;
        foreach ($settlist as $sett) {
            
            $folderlist = $em->getRepository('AppBundle:Mainfolder')
                    ->findallfolderName($sett->getId());
            //var_dump($folderlist);
            foreach ($folderlist as $folder) {
                $foldercount = array();
                
                
                $countByFolderInbox = $em->getRepository('AppBundle:Maininbox')
                        ->countallbyfolder($sett->getId(), 'Inbox');
                $countUnseenByFolderInbox = $em->getRepository('AppBundle:Maininbox')
                            ->countunseenbyFolder($sett->getId(), 'Inbox');
                $countarrayInbox = array($countByFolderInbox, $countUnseenByFolderInbox);
                $foldercount['Inbox'] = $countarrayInbox;
                
                $countByFolderSent = $em->getRepository('AppBundle:Maininbox')
                        ->countallbyfolder($sett->getId(), 'Sent');
                $countUnseenByFolderSent = $em->getRepository('AppBundle:Maininbox')
                            ->countunseenbyFolder($sett->getId(), 'Sent');
                $countarraySent = array($countByFolderSent, $countUnseenByFolderSent);
                $foldercount['Sent'] = $countarraySent;
                
                
                foreach ($folderlist as $folder) {
                    
                    $countByFolder = $em->getRepository('AppBundle:Maininbox')
                            ->countallbyfolder($sett->getId(), $folder->getFoldername());
                    
                    $countUnseenByFolder = $em->getRepository('AppBundle:Maininbox')
                            ->countunseenbyFolder($sett->getId(), $folder->getFoldername());
                    $countarray = array($countByFolder,$countUnseenByFolder);
                    
                    $foldercount[$folder->getFoldername()] = $countarray;
                }
                $settMain[$sett->getSettname()] = $foldercount;
            }
        }

        return $this->render('AppBundle:Mainemail:mainemail.html.twig', array(
                  'settMain' => $settMain, 'settlist' => $settlist, 'name' => $name,));
    }

    public function maincontactfromleadAction() {
        $em = $this->getDoctrine()->getManager();
        $leadlist = $em->getRepository('AppBundle:Lead')
                ->createmaincontact();
        //var_dump($leadlist);die;
        if ($leadlist) {
            foreach ($leadlist as $lead) {
                if ($lead['customerEmail']) {
                    $contactemail = $em->getRepository('AppBundle:Maincontact')
                            ->findOneEmail($lead['customerEmail']);
                    if (!$contactemail) {
                        $maincontact = new Maincontact();
                        $maincontact->setEmail($lead['customerEmail']);
                        $maincontact->setName($lead['customerName']);
                        $maincontact->setLeadid($lead['id']);
                        $em->persist($maincontact);
                        $em->flush();
                        var_dump($lead['customerName']);
                    }
                }
            }
        }


        return false;
    }

    public function mainautocompleteAction() {
        $em = $this->getDoctrine()->getManager();
        $autocomplete = $em->getRepository('AppBundle:Maincontact')
                ->autocomplete();
        $arraylength = count($autocomplete);
        for ($i = 0; $i < $arraylength; $i++) {
            $autocomplete[$i]['value'] = $autocomplete[$i]['name'];
        }
        //var_dump($autocomplete);die;
        $response = new Response(json_encode($autocomplete));
        return $response;
    }

    public function accountnavigationAction() {
        $em = $this->getDoctrine()->getManager();
        $allactive = $em->getRepository('AppBundle:Settings')
                ->findallactiveIncoming('1');
        $unseenarray = false;
        
        foreach ($allactive as $allact) {
            $unseen = $em->getRepository('AppBundle:Maininbox')
                    ->countunseen($allact->getId());
            $unseenarray[$allact->getId()] = $unseen;
        }

        return $this->render('AppBundle:Mainemail:sidenav.html.twig', array(
                    'unseenarray' => $unseenarray, 'accountnavigation' => $allactive,));
    }

    public function renderaccountnavAction($id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $unseenarray = [];
        $mysidesettings = $em->getRepository('AppBundle:Settings')
                ->find($id);
        $myfolders = $em->getRepository('AppBundle:Mainfolder')
                ->findallfolder($id);
        //$unseenarray = [];
        foreach ($myfolders as $myfolder) {
            $folder = $myfolder->getFoldername();
            $unseenbyfolder = $em->getRepository('AppBundle:Maininbox')
                    ->countunseenbyFolder($id, $folder);
            $unseenInteger = trim(intval($unseenbyfolder));
            $unseenarray[$folder] = $unseenInteger;
        }
        $unseen = $em->getRepository('AppBundle:Maininbox')
                ->countunseen($id);
        $unseeninbox = $em->getRepository('AppBundle:Maininbox')
                ->countunseenbyFolder($id, 'Inbox');
        return $this->render('AppBundle:Mainemail:sideaccount.html.twig', array(
                    'id' => $id, 'unseeninbox' => $unseeninbox, 'unseenarray' => $unseenarray, 'unseen' => $unseen, 'myfolders' => $myfolders, 'mysidesettings' => $mysidesettings,));
    }

    public function signatureAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $signaturelist = false;
        $choicearray = [];
        $signature = new Signature();
        $allactive = $em->getRepository('AppBundle:Settings')
                ->findallactiveIncoming('1');
        $signaturearray = $em->getRepository('AppBundle:Signature')
                ->findmysignature($name);

        if ($signaturearray) {
            foreach ($signaturearray as $sign) {
                $settid = $sign->getSettid();
                $thissettings = $em->getRepository('AppBundle:Settings')
                        ->find($settid);
                $settname = $thissettings->getSettname();
                $signaturelist[$settname] = $sign;
            }
        }
        $basesignature = '<br><hr /><p>' . $name . '</p>';
        foreach ($allactive as $all) {
            $choicearray[$all->getId()] = $all->getSettname();
        }
        $form = $this->createFormBuilder($signature)
                ->add('settid', 'choice', array(
                    'label' => 'Account',
                    'choices' => $choicearray, 'attr' => array('class' => 'input-sm')
                ))
                ->add('texthtml', 'textarea', array('data' => $basesignature, 'attr' => array('class' => 'ckeditor')))
                ->add('save', 'submit', array('label' => 'Save signature'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settid = $form->get('settid')->getData();
            $findexisting = $em->getRepository('AppBundle:Signature')
                    ->findOneBy(array('username' => $name, 'settid' => $settid));
            if ($findexisting) {
                $em->remove($findexisting);
                //$em->flush();
            }

            $signature->setUsername($name);
            $em->persist($signature);
            $em->flush();

            return $this->redirectToRoute('mainemail_signature');
        }
        return $this->render('AppBundle:Mainemail:signature.html.twig', array(
                    'signaturelist' => $signaturelist, 'form' => $form->createView(), 'name' => $name,));
    }

    public function signatureeditAction(Request $request, $id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $signature = $em->getRepository('AppBundle:Signature')
                ->find($id);
        $allactive = $em->getRepository('AppBundle:Settings')
                ->findallactiveIncoming('1');
        $signaturearray = $em->getRepository('AppBundle:Signature')
                ->findmysignature($name);

        if ($signaturearray) {
            foreach ($signaturearray as $sign) {
                $settid = $sign->getSettid();
                $thissettings = $em->getRepository('AppBundle:Settings')
                        ->find($settid);
                $settname = $thissettings->getSettname();
                $signaturelist[$settname] = $sign;
            }
        }
        $basesignature = $signature->getTexthtml();
        foreach ($allactive as $all) {
            $choicearray[$all->getId()] = $all->getSettname();
        }
        $form = $this->createFormBuilder($signature)
                ->add('settid', 'choice', array(
                    'label' => 'Account',
                    'choices' => $choicearray, 'attr' => array('class' => 'input-sm')
                ))
                ->add('texthtml', 'textarea', array('data' => $basesignature, 'attr' => array('class' => 'ckeditor')))
                ->add('save', 'submit', array('label' => 'Save signature'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $signature->setUsername($name);
            $em->persist($signature);
            $em->flush();

            return $this->redirectToRoute('mainemail_signature');
        }
        return $this->render('AppBundle:Mainemail:signature.html.twig', array(
                    'signaturelist' => $signaturelist, 'form' => $form->createView(), 'name' => $name,));
    }

    public function signaturedeleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $signature = $em->getRepository('AppBundle:Signature')
                ->find($id);
        $em->remove($signature);
        $em->flush();
        return $this->redirectToRoute('mainemail_signature');
    }

    public function maininboxAction(Request $request, $id, $page, $focusId, $folder, $success, $searchemailid) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $inboxsearch = false;
        $mysearchemail = false;
        $em = $this->getDoctrine()->getManager();
        $thissettings = $em->getRepository('AppBundle:Settings')
                ->find($id);
        $settingsname = $thissettings->getSettname();
        if ($success === 0) {
            $success = false;
        }
        $todayMidnigth = new \DateTime();
        date_modify($todayMidnigth, ' 00' . '00' . '00');
        $folders = $em->getRepository('AppBundle:Mainfolder')
                ->findallfolder($id);
        $limit = 20;

        //find out if this is a search or normal page display
        if ($focusId === 'x') {
            $offset = ($limit * $page) - $limit;
            if ($folder === 'searchemail') {
                //search for email here
                $mysearchemailObject = $em->getRepository('AppBundle:Maininbox')
                        ->find($searchemailid);
                $mysearchemail = $mysearchemailObject->getFromemail();
                $allinbox = $em->getRepository('AppBundle:Maininbox')
                        ->searchemail($id, $offset, $limit, $mysearchemail);
                $countAll = $em->getRepository('AppBundle:Maininbox')
                        ->searchemailtotalcount($id, $mysearchemail);
            } else {
                //normal page display here
                $countAll = $em->getRepository('AppBundle:Maininbox')
                        ->countallbyfolder($id, $folder);
                $allinbox = $em->getRepository('AppBundle:Maininbox')
                        ->findmyemails($id, $offset, $limit, $folder);
            }
            if (!$allinbox) {
                return $this->render('AppBundle:Mainemail:emptyinbox.html.twig', array(
                            'folder' => $folder, 'id' => $id, 'name' => $name,));
            }
            $pager = new Paginator($page, $countAll, $limit);
            $focusId = $allinbox[0]->getId();
        } else {
            $allinbox = $em->getRepository('AppBundle:Maininbox')
                    ->findFirstFocusAndLimit($id, $focusId, $limit);
            $countrest = $em->getRepository('AppBundle:Maininbox')
                    ->countrest($id, $focusId);
            $page = round($countrest / $limit);
            $countAll = $em->getRepository('AppBundle:Maininbox')
                    ->countallbyfolder($id, $folder);
            $pager = new Paginator($page, $countAll, $limit);
        }

        $search = array();
        $formsearch = $this->createFormBuilder($search)
                ->add('searchdata', 'text', array(
                    'label' => False,
                    'required' => false, 'attr' => array('class' => 'input-sm', 'placeholder' => 'search')
                ))
                //->add('search', 'submit', array('attr' => array('class' => 'btn-success btn-sm')))
                ->getForm();

        $formsearch->handleRequest($request);
        if ($formsearch->isValid()) {
            $offset = 0;
            $searchdataraw = $formsearch->get('searchdata')->getData();
            $searchdata = trim($searchdataraw);
            //search for email
            $myemailenitity = $em->getRepository('AppBundle:Maininbox')
                    ->findOneEmail($id, $searchdata);
            if ($myemailenitity) {
                $mysearchemail = $myemailenitity[0]->getFromemail();
                $searchemailid = $myemailenitity[0]->getId();
                $allinbox = $em->getRepository('AppBundle:Maininbox')
                        ->searchemail($id, $offset, $limit, $mysearchemail);
                $countAll = $em->getRepository('AppBundle:Maininbox')
                        ->searchemailtotalcount($id, $mysearchemail);
            } else {
                $mynameenitity = $em->getRepository('AppBundle:Maininbox')
                        ->findOneName($id, $searchdata);
                if ($mynameenitity) {
                    $mysearchemail = $mynameenitity[0]->getFromemail();
                    $searchemailid = $mynameenitity[0]->getId();
                    $allinbox = $em->getRepository('AppBundle:Maininbox')
                            ->searchemail($id, $offset, $limit, $mysearchemail);
                    $countAll = $em->getRepository('AppBundle:Maininbox')
                            ->searchemailtotalcount($id, $mysearchemail);
                }
            }

            if ($myemailenitity || $mynameenitity) {
                $pager = new Paginator($page, $countAll, $limit);
                $focusId = $allinbox[0]->getId();
                $success = 0;
                return $this->render('AppBundle:Mainemail:maininbox.html.twig', array(
                            'pager' => $pager, 'mysearchemail' => $mysearchemail, 'searchemailid' => $searchemailid, 'success' => $success, 'settingsname' => $settingsname, 'folder' => 'searchemail', 'folders' => $folders, 'focusId' => $focusId, 'todayMidnigth' => $todayMidnigth, $inboxsearch => $inboxsearch, 'id' => $id, 'formsearch' => $formsearch->createView(), 'page' => $page, 'name' => $name, 'allinbox' => $allinbox,));
            }
            //display starred emails
            $startid = $allinbox[0]->getId();
            $finishid = count($allinbox);
            $mystarsarray = $em->getRepository('AppBundle:Mainstar')
                    ->findmystarByNameRange($id, $name, $startid, $finishid);
            if ($mystarsarray) {
                foreach ($mystarsarray as $ms) {
                    $mystars[$ms->getInboxid()] = $ms->getInboxid();
                }
            } else {
                $mystars = false;
            }
            return $this->render('AppBundle:Mainemail:maininbox.html.twig', array(
                        'pager' => $pager, 'success' => 'noresult', 'mysearchemail' => $mysearchemail, 'searchemailid' => $searchemailid, 'settingsname' => $settingsname, 'folder' => $folder, 'folders' => $folders, 'focusId' => $focusId, 'todayMidnigth' => $todayMidnigth, $inboxsearch => $inboxsearch, 'id' => $id, 'formsearch' => $formsearch->createView(), 'page' => $page, 'name' => $name, 'allinbox' => $allinbox,));
        }

        //display starred emails
        $startid = $allinbox[0]->getId();
        $finishid = count($allinbox);
        $mystarsarray = $em->getRepository('AppBundle:Mainstar')
                ->findmystarByNameRange($id, $name, $startid, $finishid);
        if ($mystarsarray) {
            foreach ($mystarsarray as $ms) {
                $mystars[$ms->getInboxid()] = $ms->getInboxid();
            }
        } else {
            $mystars = false;
        }
        return $this->render('AppBundle:Mainemail:maininbox.html.twig', array(
                    'mystars' => $mystars, 'pager' => $pager, 'mysearchemail' => $mysearchemail, 'searchemailid' => $searchemailid, 'success' => $success, 'settingsname' => $settingsname, 'folder' => $folder, 'folders' => $folders, 'focusId' => $focusId, 'todayMidnigth' => $todayMidnigth, $inboxsearch => $inboxsearch, 'id' => $id, 'formsearch' => $formsearch->createView(), 'page' => $page, 'name' => $name, 'allinbox' => $allinbox,));
    }

    public function mainmystarAction($id, $page, $focusId, $folder, $success, $searchemailid) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $inboxsearch = false;
        $mysearchemail = false;
        $em = $this->getDoctrine()->getManager();
        $thissettings = $em->getRepository('AppBundle:Settings')
                ->find($id);
        $settingsname = $thissettings->getSettname();
        if ($success === 0) {
            $success = false;
        }
        $todayMidnigth = new \DateTime();
        date_modify($todayMidnigth, ' 00' . '00' . '00');
        $folders = $em->getRepository('AppBundle:Mainfolder')
                ->findallfolder($id);
        $limit = 20;
        $allstarsarray = $em->getRepository('AppBundle:Mainstar')
                ->findmyAllstar($id, $name);
        if (!$allstarsarray) {
            return $this->render('AppBundle:Mainemail:emptyinbox.html.twig', array(
                        'folder' => $folder, 'id' => $id, 'name' => $name,));
        }

        //find out if this is a search or normal page display
        if ($focusId === 'x') {
            $offset = ($limit * $page) - $limit;
            if ($folder === 'searchemail') {
                //search for email here
                $mysearchemailObject = $em->getRepository('AppBundle:Maininbox')
                        ->find($searchemailid);
                $mysearchemail = $mysearchemailObject->getFromemail();
                $allinbox = $em->getRepository('AppBundle:Maininbox')
                        ->searchemail($id, $offset, $limit, $mysearchemail);
                $countAll = $em->getRepository('AppBundle:Maininbox')
                        ->searchemailtotalcount($id, $mysearchemail);
            } else {
                //normal page display here
                $countAll = count($allstarsarray);
                $allinbox = $em->getRepository('AppBundle:Maininbox')
                        ->findmystars($id, $allstarsarray, $offset, $limit);
            }
            if (!$allinbox) {
                return $this->render('AppBundle:Mainemail:emptyinbox.html.twig', array(
                            'folder' => $folder, 'id' => $id, 'name' => $name,));
            }
            $pager = new Paginator($page, $countAll, $limit);
            $focusId = $allinbox[0]->getId();
        } else {
            $allinbox = $em->getRepository('AppBundle:Maininbox')
                    ->findFirstFocusAndLimit($id, $focusId, $limit);
            $countrest = $em->getRepository('AppBundle:Maininbox')
                    ->countrest($id, $focusId);
            $page = round($countrest / $limit);
            $countAll = $em->getRepository('AppBundle:Maininbox')
                    ->countallbyfolder($id, $folder);
            $pager = new Paginator($page, $countAll, $limit);
        }
        //display stars
        $startid = $allinbox[0]->getId();
        $finishid = count($allinbox);
        $mystarsarray = $em->getRepository('AppBundle:Mainstar')
                ->findmystarByNameRange($id, $name, $startid, $finishid);
        if ($mystarsarray) {
            foreach ($mystarsarray as $ms) {
                $mystars[$ms->getInboxid()] = $ms->getInboxid();
            }
        } else {
            $mystars = false;
        }
        return $this->render('AppBundle:Mainemail:mainmystar.html.twig', array(
                    'mystars' => $mystars, 'pager' => $pager, 'mysearchemail' => $mysearchemail, 'searchemailid' => $searchemailid, 'success' => $success, 'settingsname' => $settingsname, 'folder' => $folder, 'folders' => $folders, 'focusId' => $focusId, 'todayMidnigth' => $todayMidnigth, $inboxsearch => $inboxsearch, 'id' => $id, 'page' => $page, 'name' => $name, 'allinbox' => $allinbox,));
    }

    public function mainconversationAction($id, $email, $page) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $folders = $em->getRepository('AppBundle:Mainfolder')
                ->findallfolder($id);
        $limit = 10;
        $offset = ($limit * $page) - $limit;
        $myconversation = $em->getRepository('AppBundle:Maininbox')
                ->findconversation($email, $offset, $limit);
        $countAll = $em->getRepository('AppBundle:Maininbox')
                ->countconversation($email);
        $pager = new Paginator($page, $countAll, $limit);
        return $this->render('AppBundle:Mainemail:mainconversation.html.twig', array(
                    'pager' => $pager, 'email' => $email, 'name' => $name, 'folders' => $folders, 'id' => $id, 'myconversation' => $myconversation,));
    }

    public function ajaxmainconversationAction($page, $leadid) {
        $em = $this->getDoctrine()->getManager();
        $mylead = $em->getRepository('AppBundle:Lead')
                ->find($leadid);
        $email = $mylead->getCustomerEmail();

        $limit = 5;
        $offset = ($limit * $page) - $limit;
        $myconversation = $em->getRepository('AppBundle:Maininbox')
                ->findconversation($email, $offset, $limit);
        if (!$myconversation) {
            $pager = false;
            $myconversation = false;
            $html = $this->renderView('AppBundle:Mainemail:ajaxmainconversationempty.html.twig', array(
                'leadid' => $leadid, 'pager' => $pager, 'email' => $email, 'myconversation' => $myconversation,));
            $response = new Response(json_encode($html));
            return $response;
        }
        $countAll = $em->getRepository('AppBundle:Maininbox')
                ->countconversation($email);
        $pager = new Paginator($page, $countAll, $limit);
        $html = $this->renderView('AppBundle:Mainemail:ajaxmainconversation.html.twig', array(
            'leadid' => $leadid, 'pager' => $pager, 'email' => $email, 'myconversation' => $myconversation,));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function mainsentAction(Request $request, $id, $page, $searchemailid, $success) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $mysearchemail = false;
        //clear sent
        $em = $this->getDoctrine()->getManager();
        $thissettings = $em->getRepository('AppBundle:Settings')
                ->find($id);
        $settingsname = $thissettings->getSettname();
        $folders = $em->getRepository('AppBundle:Mainfolder')
                ->findallfolder($id);
        $todayMidnigth = new \DateTime();
        date_modify($todayMidnigth, ' 00' . '00' . '00');

        //clear sent
        $findnull = $em->getRepository('AppBundle:Maininbox')
                ->findnullentities($id);
        if ($findnull) {
            foreach ($findnull as $fn) {
                $em->remove($fn);
                $em->flush();
            }
        }
        $limit = 20;

        if ($searchemailid != 'non') {
            //search for email here
            $mysearchemailObject = $em->getRepository('AppBundle:Maininbox')
                    ->find($searchemailid);
            $toarray = $mysearchemailObject->getToarray();
            reset($toarray);
            $toemail = key($toarray);
            $mysearchemail = $toemail;
            $offset = ($limit * $page) - $limit;
            $allsent = $em->getRepository('AppBundle:Maininbox')
                    ->searchsentemailbyfolder($id, $offset, $limit, $toemail, 'Sent');
            $countAll = $em->getRepository('AppBundle:Maininbox')
                    ->countbyemailbysent($id, $toemail, 'Sent');
            if (!$allsent) {
                return $this->render('AppBundle:Mainemail:emptyinbox.html.twig', array(
                            'folder' => 'Sent', 'id' => $id, 'name' => $name,));
            }
            $focusId = $allsent[0]->getId();
            $pager = new Paginator($page, $countAll, $limit);
            //$pages = ceil($countAll / $limit);
        } else {
            //normal display here
            $offset = ($limit * $page) - $limit;
            $allsent = $em->getRepository('AppBundle:Maininbox')
                    ->findmysent($id, $offset, $limit);
            $countAll = $em->getRepository('AppBundle:Maininbox')
                    ->countallsent($id);
            if (!$allsent) {
                return $this->render('AppBundle:Mainemail:emptyinbox.html.twig', array(
                            'folder' => 'Sent', 'id' => $id, 'name' => $name,));
            }
            $focusId = $allsent[0]->getId();
            $pager = new Paginator($page, $countAll, $limit);
        }

        $search = array();
        $formsearch = $this->createFormBuilder($search)
                ->add('searchdata', 'text', array(
                    'label' => False,
                    'required' => false, 'attr' => array('class' => 'input-sm', 'placeholder' => 'search')
                ))
                //->add('search', 'submit', array('attr' => array('class' => 'btn-success btn-sm')))
                ->getForm();

        $formsearch->handleRequest($request);
        if ($formsearch->isValid()) {
            $searchdataraw = $formsearch->get('searchdata')->getData();
            $searchdata = trim($searchdataraw);
            $mysearchemailObject = $em->getRepository('AppBundle:Maininbox')
                    ->findOneSentEmail($id, $searchdata);
            if ($mysearchemailObject) {
                $toarray = $mysearchemailObject[0]->getToarray();
                $searchemailid = $mysearchemailObject[0]->getId();
                reset($toarray);
                $toemail = key($toarray);
                $mysearchemail = $toemail;
                $offset = ($limit * $page) - $limit;
                $allsent = $em->getRepository('AppBundle:Maininbox')
                        ->searchsentemailbyfolder($id, $offset, $limit, $toemail, 'Sent');
                $countAll = $em->getRepository('AppBundle:Maininbox')
                        ->countbyemailbysent($id, $toemail, 'Sent');
                if (!$allsent) {
                    return $this->render('AppBundle:Mainemail:emptyinbox.html.twig', array(
                                'folder' => 'Sent', 'id' => $id, 'name' => $name,));
                }
                $focusId = $allsent[0]->getId();
                $pager = new Paginator($page, $countAll, $limit);
                //$pages = ceil($countAll / $limit);
            } else {
                $mynameenitity = $em->getRepository('AppBundle:Maininbox')
                        ->findOneSentName($id, $searchdata);
                if ($mynameenitity) {
                    $toarray = $mynameenitity[0]->getToarray();
                    $searchemailid = $mynameenitity[0]->getId();
                    reset($toarray);
                    $toemail = key($toarray);
                    $mysearchemail = $toemail;
                    $offset = ($limit * $page) - $limit;
                    $allsent = $em->getRepository('AppBundle:Maininbox')
                            ->searchsentemailbyfolder($id, $offset, $limit, $toemail, 'Sent');
                    $countAll = $em->getRepository('AppBundle:Maininbox')
                            ->countbyemailbysent($id, $toemail, 'Sent');
                    if (!$allsent) {
                        return $this->render('AppBundle:Mainemail:emptyinbox.html.twig', array(
                                    'folder' => 'Sent', 'id' => $id, 'name' => $name,));
                    }
                    $focusId = $allsent[0]->getId();
                    $pager = new Paginator($page, $countAll, $limit);
                    //$pages = ceil($countAll / $limit);
                }
            }

            if ($mysearchemailObject || $mynameenitity) {
                return $this->render('AppBundle:Mainemail:mainsent.html.twig', array(
                            'pager' => $pager, 'success' => $success, 'mysearchemail' => $mysearchemail, 'formsearch' => $formsearch->createView(), 'searchemailid' => $searchemailid, 'folders' => $folders, 'settingsname' => $settingsname, 'focusId' => $focusId, 'todayMidnigth' => $todayMidnigth, 'id' => $id, 'page' => $page, 'name' => $name, 'allsent' => $allsent,));
            }
            return $this->render('AppBundle:Mainemail:mainsent.html.twig', array(
                        'pager' => $pager, 'success' => 'noresult', 'mysearchemail' => $mysearchemail, 'formsearch' => $formsearch->createView(), 'searchemailid' => $searchemailid, 'folders' => $folders, 'settingsname' => $settingsname, 'focusId' => $focusId, 'todayMidnigth' => $todayMidnigth, 'id' => $id, 'page' => $page, 'name' => $name, 'allsent' => $allsent,));
        }

        return $this->render('AppBundle:Mainemail:mainsent.html.twig', array(
                    'pager' => $pager, 'success' => $success, 'mysearchemail' => $mysearchemail, 'formsearch' => $formsearch->createView(), 'searchemailid' => $searchemailid, 'folders' => $folders, 'settingsname' => $settingsname, 'focusId' => $focusId, 'todayMidnigth' => $todayMidnigth, 'id' => $id, 'page' => $page, 'name' => $name, 'allsent' => $allsent,));
    }

    public function mainadvancedsearchAction(Request $request, $page) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $todayMidnigth = new \DateTime();
        date_modify($todayMidnigth, ' 00' . '00' . '00');

        $allactive = $em->getRepository('AppBundle:Settings')
                ->findallactiveIncoming('1');
        $accountsarray[] = 'All';
        foreach ($allactive as $all) {
            $accountsarray[$all->getId()] = $all->getSettname();
        }

        $maininboxSearch = new MaininboxSearch();
        $maininboxSearchForm = $this->createForm(new MaininboxSearchType($accountsarray), $maininboxSearch);
        $countAll = false;
        $results = false;
        $pager = false;

        $maininboxSearchForm->handleRequest($request);
        if ($maininboxSearchForm->isSubmitted() && $maininboxSearchForm->isValid()) {
            $maininboxSearchdata = $maininboxSearchForm->getData();
            $limit = $maininboxSearchdata->getPerPage();
            ;
            $newpage = $maininboxSearchdata->getPage();
            if ($newpage) {
                $page = $newpage;
                $from = ($limit * $newpage) - $limit;
            } else {
                $from = 0;
            }
            $elasticaManager = $this->container->get('fos_elastica.manager');


            $results = $elasticaManager->getRepository('AppBundle:Maininbox')->searchMaininbox($maininboxSearchdata, $limit, $from);
            $myquery = $elasticaManager->getRepository('AppBundle:Maininbox')->getQueryForSearch($maininboxSearchdata, 0, 0);
            $countAll = $this->get('fos_elastica.index.app.maininbox')->count($myquery);

            if (!$results) {
                $pager = false;
                $results = 'NO RESULTS';
                return $this->render('AppBundle:Mainemail:advancedsearch.html.twig', array(
                            'accountsarray' => $accountsarray, 'countAll' => $countAll, 'pager' => $pager, 'results' => $results, 'maininboxSearchForm' => $maininboxSearchForm->createView(), 'todayMidnigth' => $todayMidnigth, 'name' => $name,));
            }
            $pager = new Paginator($page, $countAll, $limit);
            return $this->render('AppBundle:Mainemail:advancedsearch.html.twig', array(
                        'accountsarray' => $accountsarray, 'countAll' => $countAll, 'pager' => $pager, 'results' => $results, 'maininboxSearchForm' => $maininboxSearchForm->createView(), 'todayMidnigth' => $todayMidnigth, 'name' => $name,));
        }
        return $this->render('AppBundle:Mainemail:advancedsearch.html.twig', array(
                    'countAll' => $countAll, 'pager' => $pager, 'maininboxSearchForm' => $maininboxSearchForm->createView(), 'todayMidnigth' => $todayMidnigth, 'results' => $results, 'name' => $name,));
    }

    public function maincreatenewAction($id, $success) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        if ($success === 0) {
            $success = false;
        }

        $mymessage = '<br><br><br><!--signature-->';
        $signatureobject = $em->getRepository('AppBundle:Signature')
                ->findOneBy(array('settid' => $id, 'username' => $name,));
        if ($signatureobject) {
            $mymessage = '<br><br><br><!--signature-->' . $signatureobject->getTexthtml();
        }

        $folders = $em->getRepository('AppBundle:Mainfolder')
                ->findallfolder($id);
        $thissettings = $em->getRepository('AppBundle:Settings')
                ->find($id);
        $settingsname = $thissettings->getSettname();

        return $this->render('AppBundle:Mainemail:maincreatenew.html.twig', array(
                    'mymessage' => $mymessage, 'success' => $success, 'settingsname' => $settingsname, 'folders' => $folders, 'id' => $id, 'name' => $name,));
    }

    public function mainstarAction($id, $settid) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $mystar = $em->getRepository('AppBundle:Mainstar')
                ->findmystar($id, $name);
        if (!$mystar) {
            $mainstar = new Mainstar();
            $mainstar->setUsername($name);
            $mainstar->setInboxid($id);
            $mainstar->setSettid($settid);
            $em->persist($mainstar);
            $em->flush();
            $html = 'setstar';
        } else {
            $em->remove($mystar[0]);
            $em->flush();
            $html = 'deleted';
        }
        $response = new Response(json_encode($html));
        return $response;
    }

    public function mainemailajaxactionAction(Request $request, $settid, $action) {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == "POST") {
            $emailidarray = $request->get("emailidarray");
            $action = $emailidarray[0];
            array_shift($emailidarray);
            if (!empty($emailidarray)) {
                foreach ($emailidarray as $emailid) {
                    $myemail = $em->getRepository('AppBundle:Maininbox')
                            ->find($emailid);
                    $oldfolder = $myemail->getFolder();
                    $myemail->setFolder($action);
                    $em->persist($myemail);
                    $em->flush();


                    if ($action === 'Junk') {
                        //check if there is any existing junk filter
                        $checkfilter = $em->getRepository('AppBundle:Mainfilter')
                                ->findOneBy(array('filtertext' => $myemail->getFromemail(), 'type' => 1));
                        if (!$checkfilter) {
                            $mainfilter = new Mainfilter();
                            $mainfilter->setType(1);
                            $mainfilter->setSettid($settid);
                            $mainfilter->setFiltertext($myemail->getFromemail());
                            $em->persist($mainfilter);
                            $em->flush();
                        }
                    }

                    if ($oldfolder === 'Junk') {
                        $mainfilter = $em->getRepository('AppBundle:Mainfilter')
                                ->findOneBy(array('filtertext' => $myemail->getFromemail(), 'type' => 1));
                        if ($mainfilter) {
                            $em->remove($mainfilter);
                            $em->flush();
                        }
                    }
                }
            } else {
                $html = 'empty';
                $response = new Response(json_encode($html));
                return $response;
            }
            $html = $action;
        } else {
            $html = 'action failed';
        }

        $response = new Response(json_encode($html));
        return $response;
    }

    public function renderemailAction($id, $showimage) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $blockedimg = false;
        $blockedhref = false;
        $myemail = $em->getRepository('AppBundle:Maininbox')
                ->find($id);

        $textHTML = $myemail->getTextHTML();
        $subject = $myemail->getSubject();
        $fromname = $myemail->getFromname();
        $fromemail = $myemail->getFromemail();
        $maildate = $myemail->getMaildate();
        $toarray = $myemail->getToarray();
        $ccarray = $myemail->getCcarray();
        $attachments = $em->getRepository('AppBundle:Mainattachment')
                ->findmyattachments($id);
        if (!$attachments) {
            $attachments = false;
        }

        if ($textHTML == '' || $textHTML == false) {
            $textHTML = $myemail->getContent();
        }
        $textHTMLfinal = $textHTML;
        if ($showimage === 'non') {
            if (strpos($textHTML, '<img') !== false) {
                $blockedimg = 'All links and images blocked for this email!';
            }
            if (strpos($textHTML, '<a') !== false) {
                $blockedhref = 'yes';
            }
            $textHTMLnoImage = preg_replace("/<img[^>]+\>/i", "(image) ", $textHTML);
            $textHTMLnoLink = preg_replace("/<a.+?href.+?>.+?<\/a>/is", "", $textHTMLnoImage);
            $textHTMLfinal = $textHTMLnoLink;
        }

        return $this->render('AppBundle:Mainemail:renderemail.html.twig', array(
                    'showimage' => $showimage, 'id' => $id, 'blockedimg' => $blockedimg, 'ccarray' => $ccarray, 'toarray' => $toarray, 'maildate' => $maildate, 'fromemail' => $fromemail, 'fromname' => $fromname, 'subject' => $subject, 'attachments' => $attachments, 'name' => $name, 'textHTML' => $textHTMLfinal,));
    }

    public function openemailAction($id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $blockedimg = false;
        $blockedhref = false;
        $myemail = $em->getRepository('AppBundle:Maininbox')
                ->find($id);
        $textHTML = $myemail->getTextHTML();
        $subject = $myemail->getSubject();
        $fromname = $myemail->getFromname();
        $fromemail = $myemail->getFromemail();
        $maildate = $myemail->getMaildate();
        $toarray = $myemail->getToarray();
        $ccarray = $myemail->getCcarray();
        $attachments = $em->getRepository('AppBundle:Mainattachment')
                ->findmyattachments($id);
        if (!$attachments) {
            $attachments = false;
        }

        if ($textHTML == '' || $textHTML == false) {
            $textHTML = $myemail->getContent();
        }
        if (strpos($textHTML, '<img') !== false) {
            $blockedimg = 'All links and images blocked for this email!';
        }
        if (strpos($textHTML, '<a') !== false) {
            $blockedhref = 'yes';
        }
        $textHTMLnoImage = preg_replace("/<img[^>]+\>/i", "(image) ", $textHTML);
        $textHTMLnoLink = preg_replace("/<a.+?href.+?>.+?<\/a>/is", "", $textHTMLnoImage);
        return $this->render('AppBundle:Mainemail:openemail.html.twig', array(
                    'id' => $id, 'blockedimg' => $blockedimg, 'ccarray' => $ccarray, 'toarray' => $toarray, 'maildate' => $maildate, 'fromemail' => $fromemail, 'fromname' => $fromname, 'subject' => $subject, 'attachments' => $attachments, 'name' => $name, 'textHTML' => $textHTMLnoLink,));
    }

    public function ajaxshowemailimageAction($id) {

        return false;
    }

    public function rendersentAction($id, $settid) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $myemail = $em->getRepository('AppBundle:Maininbox')
                ->find($id);
        $mysettings = $em->getRepository('AppBundle:Settings')
                ->find($settid);
        $settname = $mysettings->getSettname();
        $dirname = str_replace(' ', '_', $settname);

        $textHTML = $myemail->getTextHTML();
        $subject = $myemail->getSubject();
        $fromname = $myemail->getFromname();
        $fromemail = $myemail->getFromemail();
        $maildate = $myemail->getMaildate();
        $toarray = $myemail->getToarray();
        reset($toarray);
        $toemail = key($toarray);
        $toname = $toarray[$toemail];
        $attachments = $em->getRepository('AppBundle:Product')
                ->findmyattachments($id);
        if (!$attachments) {
            $attachments = false;
        }
        if ($textHTML == '' || $textHTML == false) {
            $textHTML = $myemail->getContent();
        }

        return $this->render('AppBundle:Mainemail:rendersent.html.twig', array(
                    'id' => $id, 'dirname' => $dirname, 'attachments' => $attachments, 'toarray' => $toarray, 'toname' => $toname, 'toemail' => $toemail, 'maildate' => $maildate, 'fromemail' => $fromemail, 'fromname' => $fromname, 'subject' => $subject, 'attachments' => $attachments, 'name' => $name, 'textHTML' => $textHTML,));
    }

    public function inboxsearch($formsearch) {

        return false;
    }

    public function ajaxformupload2Action(request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $document = new Product();
        $form = $this->createFormBuilder($document)
                ->setAction($this->generateUrl('mainemail_ajaxformupload2'))
                ->add('imageFile', 'file', array(
                    'label' => '',))
                ->add('save', 'submit', array('label' => 'Upload'))
                ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {

            $dirname = $id;
            $directoryPathOld = $this->container->getParameter('kernel.root_dir') . '/../web/images/products/';
            $directoryPathOld = preg_replace("/app..../i", "", $directoryPathOld);
            $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/Files/Emails/' . $dirname . '/';
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }

            $directoryPath = preg_replace("/app..../i", "", $directoryPath);
            $uname = $this->getUser();

            $document->setUsername($uname);
            $document->setEmailid($id);
            $document->setListname('mainemail');
            $document->setPath($directoryPath);
            $em->persist($document);
            $em->flush();

            $filename = $document->getImageName();
            $oldname = $directoryPathOld . '' . $filename;
            $newname = $directoryPath . '' . $filename;
            $attid = $document->getId();

            //remove same existing files
            $attachments = $em->getRepository('AppBundle:Product')
                    ->findallbyemail($id);
            foreach ($attachments as $att) {
                if ($att->getImageName() === $filename && $att->getId() != $document->getId()) {
                    $em->remove($att);
                    $em->flush();
                }
            }

            //move the uploaded file to email specific folder
            if (file_exists($oldname)) {
                rename($oldname, $newname);
            }

            $formhtml = $attid;
            $response = new Response(json_encode($formhtml));
            return $response;
        }

        $formhtml = $this->renderView('AppBundle:Mainemail:uploadform2.html.twig', array('form' => $form->createView(), 'id' => $id));
        $response = new Response(json_encode($formhtml));
        return $response;
    }

    public function ajaxformuploadAction(request $request, $settid, $emailid) {
        $em = $this->getDoctrine()->getManager();

        $document = new Product();
        $form = $this->createFormBuilder($document)
                ->setAction($this->generateUrl('mainemail_ajaxformupload'))
                ->add('imageFile', 'file', array(
                    'label' => '',))
                ->add('save', 'submit', array('label' => 'Upload'))
                ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            $mysettings = $em->getRepository('AppBundle:Settings')
                    ->find($settid);
            $settNameRaw = $mysettings->getSettname();
            $settNameTrimmed = trim($settNameRaw);
            $settName = str_replace(' ', '_', $settNameTrimmed);
            //$settname = $mysettings->getSettname();

            $dirname = $emailid;
            $directoryPathOld = $this->container->getParameter('kernel.root_dir') . '/../web/images/products/';
            $directoryPathOld = preg_replace("/app..../i", "", $directoryPathOld);
            $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/Files/' . $settName . '/Outgoings/' . $dirname . '/';
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }

            $directoryPath = preg_replace("/app..../i", "", $directoryPath);
            $uname = $this->getUser();

            $document->setUsername($uname);
            $document->setEmailid($emailid);
            $document->setListname('mainemail');
            $document->setPath($directoryPath);
            $em->persist($document);
            $em->flush();

            $filename = $document->getImageName();
            $oldname = $directoryPathOld . '' . $filename;
            $newname = $directoryPath . '' . $filename;
            $attid = $document->getId();

            //remove same existing files
            $attachments = $em->getRepository('AppBundle:Product')
                    ->findallbyemail($emailid);
            foreach ($attachments as $att) {
                if ($att->getImageName() === $filename && $att->getId() != $document->getId()) {
                    $em->remove($att);
                    $em->flush();
                }
            }

            //move the uploaded file to email specific folder
            if (file_exists($oldname)) {
                rename($oldname, $newname);
            }

            $formhtml = $attid;
            $response = new Response(json_encode($formhtml));
            return $response;
        }

        $formhtml = $this->renderView('AppBundle:Mainemail:uploadform.html.twig', array(
            'emailid' => $emailid, 'form' => $form->createView(), 'settid' => $settid));
        $response = new Response(json_encode($formhtml));
        return $response;
    }

    public function ajaxremoveattachmentAction($attid) {
        $em = $this->getDoctrine()->getManager();

        $attachment = $em->getRepository('AppBundle:Product')
                ->find($attid);
        $directoryPath = $attachment->getPath();
        $filename = $attachment->getImageName();

        /*
          $dirname = $attachment->getEmailId();
          $filename = $attachment->getImageName();
          $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/Files/Emails/' . $dirname . '/';
          $fullfilename = $directoryPath . '' . $filename;
         * 
         */

        $fullfilename = $directoryPath . '' . $filename;

        unlink($fullfilename);
        $em->remove($attachment);
        $em->flush();

        $html = 'removed';

        $response = new Response(json_encode($html));
        return $response;
    }

    public function maincreateemptyreplyAction($settid, $id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $newsent = new Maininbox();
        if ($id != 'non') {
            $newsent->setReplaidid($id);
        }
        $newsent->setSettid($settid);
        $newsent->setFolder('Sent');
        $em->persist($newsent);
        $em->flush();

        $sentid = $newsent->getId();

        $html = $sentid;

        $response = new Response(json_encode($html));
        return $response;
    }

    public function maincreateemptyforwardAction($settid, $id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $newsent = new Maininbox();
        if ($id != 'non') {
            $newsent->setReplaidid($id);
        }
        $newsent->setSettid($settid);
        $newsent->setFolder('Sent');
        $em->persist($newsent);
        $em->flush();

        $sentid = $newsent->getId();

        $html = $sentid;

        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxforwardattachmentcountAction($id) {
        $em = $this->getDoctrine()->getManager();
        $countattachment = $em->getRepository('AppBundle:Mainattachment')
                ->countattachmentsbyemailid($id);

        $html = $countattachment;
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxforwardattachmentdisplayAction($newid, $oldid, $settid) {
        $login = $this->getUser();
        $name = $login->getUsername();

        $em = $this->getDoctrine()->getManager();
        $attachments = $em->getRepository('AppBundle:Mainattachment')
                ->findmyattachments($oldid);
        if ($attachments) {
            $mysettings = $em->getRepository('AppBundle:Settings')
                    ->find($settid);

            $settNameRaw = $mysettings->getSettname();
            $settNameTrimmed = trim($settNameRaw);
            $settName = str_replace(' ', '_', $settNameTrimmed);
            $resultarray = [];

            foreach ($attachments as $att) {
                $product = new Product();
                $dirname = $newid;
                $directoryPathOld2nd = $att->getPath();
                $directoryPathOld = $this->container->getParameter('kernel.root_dir') . '/../web/Files/' . $directoryPathOld2nd;
                $directoryPathOld = preg_replace("/app..../i", "", $directoryPathOld);
                $filename = $att->getFilename();
                $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/Files/' . $settName . '/Outgoings/' . $dirname . '/';
                $directoryPath = preg_replace("/app..../i", "", $directoryPath);
                if (!file_exists($directoryPath)) {
                    mkdir($directoryPath, 0777, true);
                }
                $fullnewfilename = $directoryPath . '' . $filename;
                copy($directoryPathOld, $fullnewfilename);

                $checkitem = $em->getRepository('AppBundle:Product')
                        ->findOneBy(array('imageName' => $filename, 'emailid' => $newid));
                if (!$checkitem) {
                    $product = new Product();
                    $product->setImageName($filename);
                    $product->setListname('mainemail');
                    $product->setUsername($name);
                    $product->setPath($directoryPath);
                    $product->setEmailid($newid);
                    $product->setUpdatedAt(new \DateTime());
                    $em->persist($product);
                    $em->flush();
                    $resultarray[] = $product->getId();
                }
            }
            if (count($resultarray) > 0) {
                $html = $resultarray;
            } else {
                $html = 'X';
            }
            $response = new Response(json_encode($html));
            return $response;
        }
        $html = 'X';
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxsentforwardattachmentdisplayAction($newid, $oldid, $settid) {
        $login = $this->getUser();
        $name = $login->getUsername();

        $em = $this->getDoctrine()->getManager();
        $attachments = $em->getRepository('AppBundle:Product')
                ->findmyattachments($oldid);
        if ($attachments) {

            $mysettings = $em->getRepository('AppBundle:Settings')
                    ->find($settid);

            $settNameRaw = $mysettings->getSettname();
            $settNameTrimmed = trim($settNameRaw);
            $settName = str_replace(' ', '_', $settNameTrimmed);


            $resultarray = [];

            foreach ($attachments as $att) {
                $product = new Product();
                $olddirname = $oldid;
                $dirname = $newid;
                //$directoryPathOld2nd = $att->getPath();
                $directoryPathOld = $this->container->getParameter('kernel.root_dir') . '/../web/Files/' . $settName . '/Outgoings/' . $olddirname . '/';
                $directoryPathOld = preg_replace("/app..../i", "", $directoryPathOld);
                $filename = $att->getImageName();
                $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/Files/' . $settName . '/Outgoings/' . $dirname . '/';
                $directoryPath = preg_replace("/app..../i", "", $directoryPath);
                if (!file_exists($directoryPath)) {
                    mkdir($directoryPath, 0777, true);
                }
                $fullnewfilenameOld = $directoryPathOld . '' . $filename;
                $fullnewfilename = $directoryPath . '' . $filename;
                copy($fullnewfilenameOld, $fullnewfilename);
                $checkitem = $em->getRepository('AppBundle:Product')
                        ->findOneBy(array('imageName' => $filename, 'emailid' => $newid));
                if (!$checkitem) {
                    $product = new Product();
                    $product->setImageName($filename);
                    $product->setListname('mainemail');
                    $product->setUsername($name);
                    $product->setPath($directoryPath);
                    $product->setEmailid($newid);
                    $product->setUpdatedAt(new \DateTime());
                    $em->persist($product);
                    $em->flush();
                    $resultarray[] = $product->getId();
                }
            }
            if (count($resultarray) > 0) {
                $html = $resultarray;
            } else {
                $html = 'X';
            }
            $response = new Response(json_encode($html));
            return $response;
        }
        $html = 'X';
        $response = new Response(json_encode($html));
        return $response;
    }

    public function mainajaxshowattachedAction($id, $settid) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $attachment = $em->getRepository('AppBundle:Product')
                ->find($id);
        $mysettings = $em->getRepository('AppBundle:Settings')
                ->find($settid);
        $settNameRaw = $mysettings->getSettname();
        $settName = trim($settNameRaw);
        $dirname = str_replace(' ', '_', $settName);

        $directoryId = $attachment->getEmailid();
        $html = $this->renderView('AppBundle:Mainemail:ajaxshowattached.html.twig', array(
            'dirname' => $dirname, 'directoryId' => $directoryId, 'id' => $id, 'attachment' => $attachment));

        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxcreatenewformAction($settid) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $id = false;
        $email = false;
        $mymessage = '<br><br><br><!--signature-->';
        $mysubject = false;
        $signatureobject = $em->getRepository('AppBundle:Signature')
                ->findOneBy(array('settid' => $settid, 'username' => $name,));
        if ($signatureobject) {
            $mymessage = '<br><br><br><!--signature-->' . $signatureobject->getTexthtml();
        }
        $html = $this->renderView('AppBundle:Mainemail:mainajaxnewmemailform.html.twig', array(
            'id' => $id, 'email' => $email, 'mymessage' => $mymessage, 'mysubject' => $mysubject,));

        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxemailformAction($id, $type, $settid) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $email = false;
        $date = false;
        $toemail = false;
        $toname = false;
        $toarray = false;

        $myemail = $em->getRepository('AppBundle:Maininbox')
                ->find($id);
        $signatureobject = $em->getRepository('AppBundle:Signature')
                ->findOneBy(array('settid' => $settid, 'username' => $name,));
        if ($signatureobject) {
            $signature = $signatureobject->getTexthtml();
        } else {
            $signature = false;
        }
        $subject = $myemail->getSubject();
        $message = $myemail->getTextHTML();
        if ($message == '' || $message == false) {
            $message = $myemail->getContent();
        }

        if ($type === 'reply') {
            $email = $myemail->getFromemail();
            $date = $myemail->getMaildate();
            $toemail = $myemail->getToemail();
            $toname = $myemail->getFromname();
            $replyheading = $this->renderView('AppBundle:Mainemail:replyheading.html.twig', array(
                'toemail' => $toemail, 'email' => $email, 'date' => $date, 'subject' => $subject,));
            $mymessage = '<br><br><br><!--signature-->' . $signature . '<br><br>' . $replyheading . '<hr>' . $message;
            $mysubject = 'Re: ' . $subject;
            $html = $this->renderView('AppBundle:Mainemail:mainajaxemailform.html.twig', array(
                'toarray' => $toarray, 'toname' => $toname, 'id' => $id, 'email' => $email, 'mymessage' => $mymessage, 'mysubject' => $mysubject,));
            $response = new Response(json_encode($html));
            return $response;
        } elseif ($type === 'forward') {
            $email = $myemail->getFromemail();
            $date = $myemail->getMaildate();
            $toemail = $myemail->getToemail();
            $forwardheading = $this->renderView('AppBundle:Mainemail:forwardheading.html.twig', array(
                'toemail' => $toemail, 'email' => $email, 'date' => $date, 'subject' => $subject,));
            $mymessage = '<br><br><br><!--signature-->' . $signature . '<br><br>' . $forwardheading . '<hr>' . $message;
            $mysubject = 'Fw: ' . $subject;
            $html = $this->renderView('AppBundle:Mainemail:mainajaxsendemail.html.twig', array(
                'toarray' => $toarray, 'toname' => $toname, 'id' => $id, 'email' => $email, 'mymessage' => $mymessage, 'mysubject' => $mysubject,));
            $response = new Response(json_encode($html));
            return $response;
        } elseif ($type === 'replyall') {
            $emailraw = $myemail->getFromemail();
            $email = strtolower(trim($emailraw));
            $toarray = $myemail->getToarray();
            $date = $myemail->getMaildate();
            $toemail = $myemail->getToemail();
            $forwardheading = $this->renderView('AppBundle:Mainemail:replyallheading.html.twig', array(
                'toemail' => $toemail, 'email' => $email, 'date' => $date, 'subject' => $subject,));
            $mymessage = '<br><br><br><!--signature-->' . $signature . '<br><br>' . $forwardheading . '<hr>' . $message;
            $mysubject = 'Re: ' . $subject;
            $html = $this->renderView('AppBundle:Mainemail:mainajaxreplyall.html.twig', array(
                'toarray' => $toarray, 'toname' => $toname, 'id' => $id, 'email' => $email, 'mymessage' => $mymessage, 'mysubject' => $mysubject,));
            $response = new Response(json_encode($html));
            return $response;
        }
        $html = $this->renderView('AppBundle:Mainemail:mainajaxemailform.html.twig', array(
            'toarray' => $toarray, 'toname' => $toname, 'id' => $id, 'email' => $email, 'mymessage' => $mymessage, 'mysubject' => $mysubject,));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxsentforwardformAction($id, $settid) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $toarray = false;
        $toname = false;
        $myemail = $em->getRepository('AppBundle:Maininbox')
                ->find($id);
        $signatureobject = $em->getRepository('AppBundle:Signature')
                ->findOneBy(array('settid' => $settid, 'username' => $name,));
        if ($signatureobject) {
            $signature = $signatureobject->getTexthtml();
        } else {
            $signature = false;
        }
        $subject = $myemail->getSubject();
        $message = $myemail->getTexthtml();
        $email = $myemail->getFromemail();
        $date = $myemail->getMaildate();
        $toemail = $myemail->getToemail();
        $forwardheading = $this->renderView('AppBundle:Mainemail:forwardheading.html.twig', array(
            'toemail' => $toemail, 'email' => $email, 'date' => $date, 'subject' => $subject,));
        $mymessage = '<br><br><br><!--signature-->' . $signature . '<br><br>' . $forwardheading . '<hr>' . $message;
        $mysubject = 'Fw: ' . $subject;
        $html = $this->renderView('AppBundle:Mainemail:mainajaxsendemail.html.twig', array(
            'toarray' => $toarray, 'toname' => $toname, 'id' => $id, 'email' => $email, 'mymessage' => $mymessage, 'mysubject' => $mysubject,));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxseenAction($id) {
        $em = $this->getDoctrine()->getManager();
        $myemail = $em->getRepository('AppBundle:Maininbox')
                ->find($id);
        if ($myemail) {
            $myemail->setSeen(1);
            $em->flush();
            $html = 'set to seen';
            $response = new JsonResponse($html);
            return $response;
        }
        $html = 'could not find';
        $response = new JsonResponse($html);
        return $response;
    }

    public function ajaxunseenAction($id) {
        $em = $this->getDoctrine()->getManager();
        $myemail = $em->getRepository('AppBundle:Maininbox')
                ->find($id);
        if ($myemail) {
            $myemail->setSeen(0);
            $em->flush();
            $html = 'set to unseen';
            $response = new JsonResponse($html);
            return $response;
        }
        $html = 'could not find';
        $response = new JsonResponse($html);
        return $response;
    }

    public function mainsendemailAction(Request $request, $type) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == "POST") {
            $emailcc = false;
            $settid = $request->get("settid");
            $sentid = $request->get("sentid");
            $maininboxid = $request->get("maininboxid");
            $sendcc = $request->get("sendcc");
            $sendbcc = $request->get("sendbcc");
            $subject = $request->get("subject");
            $tonameform = $request->get("toname");
            $mymessage = $request->get("editor1");
            $reloadpage = $request->get("reloadpage");
            $reloadfocusid = $request->get("reloadfocusid");
            $signaturePos = strpos($mymessage, '<!--signature-->');
            if ($signaturePos) {
                $mymessageCut = substr($mymessage, 0, $signaturePos);
                $content = strip_tags($mymessageCut);
            } else {
                $content = strip_tags($mymessage);
            }
            $mycontentraw = str_replace('&nbsp;', '', $content);
            $mycontent = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $mycontentraw);
            $mysent = $em->getRepository('AppBundle:Maininbox')
                    ->find($sentid);

            if ($type === 'replysend') {
                $replaidId = $mysent->getReplaidid();
                $replyoriginal = $em->getRepository('AppBundle:Maininbox')
                        ->find($replaidId);
                $replaytoarray = $replyoriginal->getReplyto();
                $email = $replaytoarray;
            } elseif ($type === 'replyallsend') {
                $replaidId = $mysent->getReplaidid();
                $replyoriginal = $em->getRepository('AppBundle:Maininbox')
                        ->find($replaidId);
                $replaytoarray = $replyoriginal->getReplyto();
                $email = $replaytoarray;
                $ccreplaytoallarray = $replyoriginal->getToarray();
                $emailcc = $ccreplaytoallarray;
            } elseif ($type === 'regularsend') {
                $sendto = $request->get("sendto");
                $trimmedemail = strtolower(trim($sendto));
                $emailstring = str_replace(array("\r", "\n"), '', $trimmedemail);
                if ($emailstring == '') {
                    return $this->redirectToRoute('mainemail_inbox', array('id' => $settid, 'page' => $reloadpage, 'focusId' => $reloadfocusid,));
                }
                if ($tonameform == '') {
                    $email = array($emailstring => 'Client');
                } else {
                    $email = array($emailstring => $tonameform);
                }
            }
            $thissettings = $em->getRepository('AppBundle:Settings')
                    ->find($settid);
            $smtp = $thissettings->getSmtp();
            $port = $thissettings->getPort();
            $mssl = $thissettings->getEssl();
            $euser = $thissettings->getEusername();
            $epass = $thissettings->getEpassword();
            $auth = $thissettings->getAuth();
            $fromname = $thissettings->getFromname();

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
                    //->setTo(array($email => 'Client'))
                    ->setBody($mymessage, 'text/html')
            ;

            if ($email) {
                $message->setTo($email);
            }
            if ($emailcc) {
                $message->setTo($emailcc);
            }
            if (strpos($sendbcc, '@') !== false) {
                $message->addBcc($sendbcc);
            }
            if (strpos($sendcc, '@') !== false) {
                $message->addCc($sendcc);
            }



            $attachmentarray = $em->getRepository('AppBundle:Product')
                    ->findallbyemail($sentid);
            if ($attachmentarray) {
                foreach ($attachmentarray as $att) {
                    $attName = $att->getImageName();
                    $attPath = $att->getPath();
                    $path = $attPath . $attName;
                    if ($path) {
                        $message->attach(\Swift_Attachment::fromPath($path));
                    }
                }
            }

            try {
                $mailer->getTransport()->start();
                $mailer->send($message);
                $mailer->getTransport()->stop();

                reset($email);
                $toemail = key($email);
                $toname = $email[$toemail];
                $mysent->setMaildate(new \DateTime());
                $mysent->setSettid($settid);
                $mysent->setFromemail($euser);
                $mysent->setFromname($fromname);
                $mysent->setSubject($subject);
                $mysent->setFolder('Sent');
                $mysent->setTexthtml($mymessage);
                $mysent->setContent($mycontent);
                $mysent->setToarray($email);
                $mysent->setToname($toname);
                $mysent->setToemail($toemail);
                if ($emailcc) {
                    $mysent->setCcarray($emailcc);
                }
                $mysent->setUsername($name);
                if ($attachmentarray) {
                    $mysent->setAttachment(1);
                }
                $em->persist($mysent);
                $em->flush();

                $maininbox = $em->getRepository('AppBundle:Maininbox')
                        ->find($maininboxid);
                if ($maininbox) {
                    $maininbox->setReplaid($mysent->getId());
                    $em->persist($maininbox);
                    $em->flush();
                }
            } catch (Swift_TransportException $e) {
                $mailer->getTransport()->stop();
                $error = true;
                return false;
            } catch (Exception $e) {
                $mailer->getTransport()->stop();
                $error = true;
                return false;
            }
        }
        if ($reloadpage === 'redirecttocreatenew') {
            return $this->redirectToRoute('mainemail_createnew', array('id' => $settid, 'success' => 'success'));
        } elseif ($reloadpage === 'redirecttosent') {
            return $this->redirectToRoute('mainemail_sent', array('id' => $settid, 'success' => 'success'));
        }

        return $this->redirectToRoute('mainemail_inbox', array('id' => $settid, 'page' => $reloadpage, 'focusId' => $reloadfocusid, 'success' => 'success'));
    }

    public function downloadinboxAction($id) {

        $em = $this->getDoctrine()->getManager();
        $thisSettings = $em->getRepository('AppBundle:Settings')->find($id);
        $downloadLimit = 30;
        $dirname = $thisSettings->getDirname();
        $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/Files/' . $dirname . '/';
        $directoryPath = preg_replace("/app..../i", "", $directoryPath);
        $incomingSsl = $thisSettings->getIncomingSSL();
        $imapServer = $thisSettings->getImapserver();
        $imapPort = $thisSettings->getImapport();
        $esuername = $thisSettings->getEusername();
        $epassword = $thisSettings->getEpassword();
        $lastemail = $thisSettings->getLastemail();
        $connectionString = '{' . $imapServer . ':' . $imapPort . '}INBOX';

        $mailbox = new Mailbox($connectionString, $esuername, $epassword, $directoryPath);
        $mailsIds = $mailbox->searchMailBox('ALL');
        $spamfilter = $em->getRepository('AppBundle:Mainfilter')
                ->findallspam();
        $myrulesFrom = $em->getRepository('AppBundle:Mainmessagerules')
                ->selectallrules($id, 'from');
        $myrulesSubject = $em->getRepository('AppBundle:Mainmessagerules')
                ->selectallrules($id, 'subject');
        $whitelist = array('zip', 'pdf', 'jpg', 'png', 'png', 'doc', 'docx', 'xls', 'xlsx', 'txt');
        if (!$lastemail) {
            $firstKey = 0;
        } else {
            $firstKey = array_search($lastemail, $mailsIds) + 1;
        }
        $slicedMailsIds = array_slice($mailsIds, $firstKey, $downloadLimit);

        foreach ($slicedMailsIds as $mid) {
            $mailId = (int) $mid;
            $mail = $mailbox->getMail($mailId);
            $textHtml = $mail->textHtml;
            $mycontent = $mail->textPlain;
            $contentclearedRaw = strip_tags($mycontent);
            $contentraw = str_replace('&nbsp;', '', $contentclearedRaw);
            $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $contentraw);

            $fromEMAIL = $mail->fromAddress;
            $toEMAIL = $mail->toString;
            $replyTO = $mail->replyTo;
            $toarray = $mail->to;
            $ccarray = $mail->cc;
            $fromNAME = $mail->fromName;
            if (!$content || $content === '') {
                $textclearedRaw = strip_tags($textHtml);
                $textcleared = str_replace('&nbsp;', '', $textclearedRaw);
                $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $textcleared);
            }
            if ($fromNAME === '' || $fromNAME == false) {
                $fromNAME = substr($fromEMAIL, 0, strpos($fromEMAIL, "@"));
            }
            $subject = $mail->subject;
            $mailDateString = $mail->date;
            $mailDate = new \DateTime($mailDateString);
            $maininbox = new Maininbox();

            //spam filter
            if ($spamfilter) {
                foreach ($spamfilter as $spamtext) {
                    if ($spamtext['filtertext'] == $fromEMAIL) {
                        $maininbox->setFolder('Junk');
                        break;
                    }
                }
            }
            //rule filter
            $checkfolder = $maininbox->getFolder();
            if (!$checkfolder) {
                if ($myrulesFrom) {
                    foreach ($myrulesFrom as $myrule) {
                        if ($myrule['filtertext'] == $fromEMAIL) {
                            $maininbox->setFolder($myrule['folder']);
                            break;
                        }
                    }
                }
            }
            $checkfolder2 = $maininbox->getFolder();
            if (!$checkfolder2) {
                if ($myrulesSubject) {
                    foreach ($myrulesSubject as $myrule) {
                        if (strpos($subject, $myrule['filtertext']) !== false) {
                            $maininbox->setFolder($myrule['folder']);
                            break;
                        }
                    }
                }
            }

            $checkfolder3 = $maininbox->getFolder();
            if (!$checkfolder3) {
                $maininbox->setFolder('Inbox');
            }
            $maininbox->setSettings($thisSettings);
            $maininbox->setFromemail($fromEMAIL);
            $maininbox->setToemail($toEMAIL);
            $maininbox->setReplyto($replyTO);
            $maininbox->setToarray($toarray);
            $maininbox->setCcarray($ccarray);
            $maininbox->setFromname($fromNAME);
            $maininbox->setSubject($subject);
            $maininbox->setMailid($mailId);
            $maininbox->setSettid($id);
            $maininbox->setContent($content);
            if ($textHtml) {
                $maininbox->setTexthtml($textHtml);
            }
            $maininbox->setCreatedAt(new \DateTime());
            $maininbox->setMaildate($mailDate);
            $maininbox->setPublicid($mailId);
            $maininbox->setSeen(0);
            $thisSettings->setLastemail($mailId);
            $thisSettings->setLastdownload(new \DateTime());
            $em->persist($thisSettings);
            $em->persist($maininbox);
            $em->flush();

            $attachments = $mail->getAttachments();
            if ($attachments) {
                foreach ($attachments as $att) {

                    $attachmentStatus = false;
                    $fileName = $att->name;
                    $filePath = $att->filePath;
                    $extension = substr(strrchr($fileName, '.'), 1);
                    foreach ($whitelist as $whl) {
                        if ($extension === $whl) {
                            $attachmentStatus = true;
                            break;
                        }
                    }
                    if ($attachmentStatus === false) {
                        unlink($filePath);
                        $attpublicid = $att->id;
                        $mainattachment = new Mainattachment();
                        $mainattachment->setSettid($id);
                        $mainattachment->setEmailpublicid($mailId);
                        $mainattachment->setAttpublicid($attpublicid);
                        $mainattachment->setFileName('Attachment removed for security reasons!');
                        $mainattachment->setPath('removed');

                        $mainattachment->setCreatedAt(new \DateTime());
                        $mainattachment->setMaininbox($maininbox);
                        $maininbox->setAttachment(1);
                        $em->persist($mainattachment);
                        $em->persist($maininbox);
                        $em->flush();
                    } else {
                        if ($extension === 'zip') {
                            var_dump('this is zip, do something!');
                        }
                        $mainattachment = new Mainattachment();
                        $attpublicid = $att->id;
                        $filename = $att->name;
                        $fullpath = $att->filePath;
                        $path = substr($fullpath, strpos($fullpath, "Files") + 6);

                        $mainattachment->setSettid($id);
                        $mainattachment->setEmailpublicid($mailId);
                        $mainattachment->setAttpublicid($attpublicid);
                        $mainattachment->setFileName($filename);
                        $mainattachment->setPath($path);

                        $mainattachment->setCreatedAt(new \DateTime());
                        $mainattachment->setMaininbox($maininbox);
                        $maininbox->setAttachment(1);
                        $em->persist($mainattachment);
                        $em->persist($maininbox);
                        $em->flush();
                    }
                }
            }
            //create new contact
            $contactemail = $em->getRepository('AppBundle:Maincontact')
                    ->findOneEmail($fromEMAIL);
            if (!$contactemail) {
                $maincontact = new Maincontact();
                $maincontact->setEmail($fromEMAIL);
                $maincontact->setName($fromNAME);
                $em->persist($maincontact);
                $em->flush();
            }
        }
        return $this->redirectToRoute('mainemail_inbox', array('id' => $id));
    }

    public function settingsAction(request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $error = false;
        $settlist = $em->getRepository('AppBundle:Settings')->findAll();
        $settings = new Settings();
        $form = $this->createForm(new SettingsType(), $settings);
        $form2 = $this->createForm(new SettingssmtpType(), $settings);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $settNameRaw = $data->getSettname();
            $settName = trim($settNameRaw);
            $dirname = str_replace(' ', '_', $settName);
            $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/Files/' . $dirname . '/';
            $directoryPathOutgoings = $this->container->getParameter('kernel.root_dir') . '/../web/Files/' . $dirname . '/Outgoings/';

            foreach ($settlist as $sett) {
                $existname = $sett->GetSettname();
                if ($existname === $settName) {
                    $error = 'Name already exist!';
                    return $this->render('AppBundle:Mainemail:settings.html.twig', array(
                                'error' => $error, 'form' => $form->createView(), 'form2' => $form2->createView(), 'name' => $name, 'settlist' => $settlist,
                    ));
                }
            }
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }
            if (!file_exists($directoryPathOutgoings)) {
                mkdir($directoryPathOutgoings, 0777, true);
            }

            //persit data to database
            $settings->setActive('1');
            $settings->setIncoming('1');
            $settings->setCreatedAt();
            $settings->setUsername($name);
            $settings->setDirname($dirname);
            $em->persist($settings);
            $em->flush();
            $settNewId = $settings->getId();
            $folder = new Mainfolder();
            $folder->setSettid($settNewId);
            $folder->setFoldername('Archive');
            $folder->setUsername($name);
            $folder->setCreatedAt(new \DateTime());
            $em->persist($folder);
            $em->flush();
            $folder2 = new Mainfolder();
            $folder2->setSettid($settNewId);
            $folder2->setFoldername('Junk');
            $folder2->setUsername($name);
            $folder2->setCreatedAt(new \DateTime());
            $em->persist($folder2);
            $em->flush();

            return $this->redirectToRoute('mainemail_settings');
        }

        $form2->handleRequest($request);

        if ($form2->isValid()) {

            $data = $form->getData();
            $settNameRaw = $data->getSettname();
            $settName = trim($settNameRaw);
            $dirname = str_replace(' ', '_', $settName);
            $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/Files/' . $dirname . '/';
            $directoryPathOutgoings = $this->container->getParameter('kernel.root_dir') . '/../web/Files/' . $dirname . '/Outgoings/';
            foreach ($settlist as $sett) {
                $existname = $sett->GetSettname();
                if ($existname === $settName) {
                    $error = 'Name already exist!';
                    return $this->render('AppBundle:Mainemail:settings.html.twig', array(
                                'error' => $error, 'form' => $form->createView(), 'form2' => $form2->createView(), 'name' => $name, 'settlist' => $settlist,
                    ));
                }
            }
            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }
            if (!file_exists($directoryPathOutgoings)) {
                mkdir($directoryPathOutgoings, 0777, true);
            }

            //persit data to database
            $settings->setCreatedAt();
            $settings->setActive('1');
            $settings->setIncoming('1');
            $settings->setUsername($name);
            $settings->setDirname($dirname);
            $em->persist($settings);
            $em->flush();
            $settNewId = $settings->getId();
            $folder = new Mainfolder();
            $folder->setSettid($settNewId);
            $folder->setFoldername('Archive');
            $folder->setUsername($name);
            $folder->setCreatedAt(new \DateTime());
            $em->persist($folder);
            $em->flush();
            $folder2 = new Mainfolder();
            $folder2->setSettid($settNewId);
            $folder2->setFoldername('Junk');
            $folder2->setUsername($name);
            $folder2->setCreatedAt(new \DateTime());
            $em->persist($folder2);
            $em->flush();

            return $this->redirectToRoute('mainemail_settings');
        }

        return $this->render('AppBundle:Mainemail:settings.html.twig', array(
                    'error' => $error, 'form' => $form->createView(), 'form2' => $form2->createView(), 'name' => $name, 'settlist' => $settlist,
        ));
    }

    public function maindeletesettingsAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Settings')->find($id);
        $dirname = $entity->getDirname();
        $directoryPath = $this->container->getParameter('kernel.root_dir') . '/../web/Files/' . $dirname . '/';

        if (file_exists($directoryPath)) {
            array_map('unlink', glob("$directoryPath/*.*"));
            rmdir($directoryPath);
        }
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('mainemail_settings'));
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

        return $this->redirect($this->generateUrl('mainemail_settings'));
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

        return $this->redirect($this->generateUrl('mainemail_settings'));
    }

    public function maincreatefolderAction($settid, $foldername) {
        $login = $this->getUser();
        $username = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $foldername = trim($foldername);
        $mainfolder = new Mainfolder();
        $mainfolder->setUsername($username);
        $mainfolder->setFoldername($foldername);
        $mainfolder->setSettid($settid);
        $mainfolder->setCreatedAt(new \DateTime());
        $em->persist($mainfolder);
        $em->flush();
        $html = 'success';
        $response = new JsonResponse($html);
        return $response;
    }

    public function foldersettingsAction(Request $request, $id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $myfoldersarray = $em->getRepository('AppBundle:Mainfolder')
                ->findallfolder($id);
        $mymessagerules = $em->getRepository('AppBundle:Mainmessagerules')
                ->findallrules($id);
        foreach ($myfoldersarray as $myfolder) {
            $emails = $em->getRepository('AppBundle:Maininbox')
                    ->countallbyfolder($id, $myfolder->getFoldername());
            $myfolderscount[$myfolder->getId()] = $emails;
        }
        $inboxcount = $em->getRepository('AppBundle:Maininbox')
                ->countallbyfolder($id, 'Inbox');
        $thissettings = $em->getRepository('AppBundle:Settings')
                ->find($id);
        $inboxCreatedAt = $thissettings->getCreatedAt();

        foreach ($myfoldersarray as $myfolder) {
            $actionarray[$myfolder->getFoldername()] = $myfolder->getFoldername();
        }
        $typearray = array('from' => 'If the from line contains email address move to folder', 'subject' => 'If the subject line contains text address move to folder');

        $mainmessagerule = new Mainmessagerules();
        $form = $this->createFormBuilder($mainmessagerule)
                ->add('type', 'choice', array(
                    'label' => 'Rule type',
                    'choices' => $typearray, 'attr' => array('class' => 'input-sm')
                ))
                ->add('rulename', 'text', array(
                    'label' => 'Rule Name', 'data' => '', 'attr' => array('class' => 'input-sm')))
                ->add('folder', 'choice', array(
                    'label' => 'Move to',
                    'choices' => $actionarray, 'attr' => array('class' => 'input-sm')
                ))
                ->add('filtertext', 'text', array(
                    'label' => 'Text / Email', 'data' => '', 'attr' => array('class' => 'input-sm')))
                ->add('save', 'submit', array('label' => 'Create'))
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mytype = $form->get('type')->getData();
            $myfiltertext = $form->get('filtertext')->getData();
            if ($mytype === 'from') {
                if (strpos($myfiltertext, '@') !== false) {
                    $mainmessagerule->setUsername($name);
                    $mainmessagerule->setSettid($id);
                    $mainmessagerule->setUsername($name);
                    $mainmessagerule->setCreatedAt(new \DateTime());
                    $em->persist($mainmessagerule);
                    $em->flush();
                }
                return $this->redirectToRoute('mainemail_foldersettings', array('id' => $id));
            }
            $mainmessagerule->setUsername($name);
            $mainmessagerule->setSettid($id);
            $mainmessagerule->setUsername($name);
            $mainmessagerule->setCreatedAt(new \DateTime());
            $em->persist($mainmessagerule);
            $em->flush();
            return $this->redirectToRoute('mainemail_foldersettings', array('id' => $id));
        }
        return $this->render('AppBundle:Mainemail:mainfoldersettings.html.twig', array(
                    'form' => $form->createView(), 'mymessagerules' => $mymessagerules, 'inboxCreatedAt' => $inboxCreatedAt, 'inboxcount' => $inboxcount, 'myfolderscount' => $myfolderscount, 'id' => $id, 'name' => $name, 'myfolders' => $myfoldersarray,
        ));
    }

    public function mainformfromAction(Request $request, $id) {
        if ($request->getMethod() == "POST") {
            $rulename = $request->get("form_rulename");
            $folder = $request->get("folder");
            $mainmessageruleFrom->setUsername($name);
            $mainmessageruleFrom->setSettid($id);
            $mainmessageruleFrom->setUsername($name);
            $mainmessageruleFrom->setType('from');
            $mainmessageruleFrom->setCreatedAt(new \DateTime());
            $em->persist($mainmessageruleFrom);
            $em->flush();
            return $this->redirectToRoute('mainemail_foldersettings', array('id' => $id));
        }
        return false;
    }

    public function mainruledeleteAction($id, $settid) {
        $em = $this->getDoctrine()->getManager();
        $myrule = $em->getRepository('AppBundle:Mainmessagerules')
                ->find($id);
        $em->remove($myrule);
        $em->flush();
        return $this->redirectToRoute('mainemail_foldersettings', array('id' => $settid));
    }

    public function mainfolderdeleteAction($id, $settid) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $myfolder = $em->getRepository('AppBundle:Mainfolder')
                ->find($id);
        //check emails
        $em->remove($myfolder);
        $em->flush();
        return $this->redirectToRoute('mainemail_foldersettings', array('id' => $settid));
    }

}
