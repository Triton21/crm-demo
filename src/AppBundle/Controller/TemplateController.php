<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Lead;
use AppBundle\Entity\Callhistory;
use AppBundle\Form\LeadType;

class TemplateController extends Controller {

    public function newformAction(Request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $lead = new Lead();
        $form = $this->createForm(new LeadType(), $lead, array(
            'action' => $this->generateUrl('template_new'),
            'method' => 'POST',));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $lead = $form->getData();
            $email = $lead->getCustomerEmail();
            $message = $lead->getMessage();
            $callhistory = new Callhistory();
            if ($email) {
                $checkemail = $em->getRepository('AppBundle:Lead')->findOneBy(array('customerEmail' => $email));
                if (!$checkemail) {
                    $customername = $lead->getCustomerName();
                    $splitnamearray = $this->namesplit($customername);
                    $lead->setSurname($splitnamearray[1]);
                    $lead->setFirstname($splitnamearray[0]);
                    $lead->setCreatedAt(new \DateTime());
                    $lead->setLastcontact(new \DateTime());
                    $lead->setStatus('new');
                    $lead->setContacted(1);
                    $lead->setAssign($name);
                    $em->persist($lead);
                    $em->flush();
                    $getbackid = $em->getRepository('AppBundle:Lead')->findOneBy(array('customerEmail' => $email));
                    $id = $getbackid->getId();
                    $em->flush();
                    $callhistory->setLeadid($id);
                    $callhistory->setNote($message);
                    $callhistory->setCalldate(new \DateTime());
                    $callhistory->setAssign($name);
                    $callhistory->setStatus('Called');
                    $em->persist($callhistory);
                    $em->flush();
                    
                    return $this->redirectToRoute('lead_progresstest', array('id' => $id));
                }
                $id = $checkemail->getId();
                return $this->redirectToRoute('lead_progresstest', array('id' => $id));
            }
            $customername = $lead->getCustomerName();
            $splitnamearray = $this->namesplit($customername);
            $lead->setSurname($splitnamearray[1]);
            $lead->setFirstname($splitnamearray[0]);
            $lead->setCreatedAt(new \DateTime());
            $lead->setStatus('new');
            $lead->setContacted(1);
            $lead->setLastcontact(new \DateTime());
            $lead->setAssign($name);
            $em->persist($lead);
            $em->flush();
            return $this->redirectToRoute('lead_newajax');
        }

        return $this->render('AppBundle:Default:templatenew.html.twig', array('form' => $form->createView(), 'name' => $name,));
    }

    public function searchformAction(Request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();
        
        $search = array();
        $namesearch = False;
        $emailsearch = False;
        $telsearch = False;
        $formsearch = $this->createFormBuilder($search)
                ->add('name', 'text', array(
                    'label' => 'Name',
                    'required' => false, 'attr' => array('class' => 'input-sm', 'placeholder' => 'search')
                ))
                ->add('search', 'submit', array('attr' => array('class' => 'btn-success btn-sm')))
                ->setAction($this->generateUrl('template_search'))
                ->getForm();
        $formsearch->handleRequest($request);
        if ($formsearch->isValid()) {
            $searchresult = $this->search($formsearch);
            $namesearch = $searchresult[0];
            $emailsearch = $searchresult[1];
            $telsearch = $searchresult[2];
            return $this->render('AppBundle:Default:searchresult.html.twig', array('telsearch' => $telsearch, 'namesearch' => $namesearch, 'emailsearch' => $emailsearch, 'countlead' => $countlead, 'name' => $name, ));
        }

        return $this->render('AppBundle:Default:templatesearch.html.twig', array('formsearch' => $formsearch->createView(), 'name' => $name,));
    }

    public function search($formsearch) {

        $result = array();
        if ($formsearch->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $formsearch->getData();
            //if name field has item it will try to find in the databae
            if ($data['name']) {
                $em = $this->getDoctrine()->getManager();
                $namesearch = $em->getRepository('AppBundle:Lead')
                        ->searchLeadsByName($data['name']);
                if (!$namesearch) {
                    $namesearch = '';
                }
                $result[] = $namesearch;

                //emailsearch
                $em = $this->getDoctrine()->getManager();
                $emailsearch = $em->getRepository('AppBundle:Lead')
                        ->searchLeadsByEmail($data['name']);
                if (!$emailsearch) {
                    $emailsearch = '';
                }
                $result[] = $emailsearch;

                //tel search
                $em = $this->getDoctrine()->getManager();
                $telsearch = $em->getRepository('AppBundle:Lead')
                        ->searchLeadsByPhone($data['name']);
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
    
    public function countlead() {
        //COUNT ALL LEADS, WON, TODO(IN PROGRESS), NEW, DEAD
        $login = $this->getUser();
        $name = $login->getUsername();

        $countlead = array();
        $em = $this->getDoctrine()->getManager();

        //COUNT ALL LEADS, WON, TODO(IN PROGRESS), NEW, DEAD
        $allwon = $em->getRepository('AppBundle:Lead')
                ->countstatus('Won');

        $mywonthismonth = $em->getRepository('AppBundle:Lead')
                ->countstatusbynamethismonth($name, 'Won');

        $inprogress = $em->getRepository('AppBundle:Lead')
                ->countstatus('In progress');

        $newcount = $em->getRepository('AppBundle:Lead')
                ->countstatus('new');

        $pendingcount = $em->getRepository('AppBundle:Lead')
                ->countstatus('Pending');

        $mytodo = $em->getRepository('AppBundle:Lead')
                ->counttodobyname($name);

        $all = $em->getRepository('AppBundle:Lead')
                ->countall();

        $flag = $em->getRepository('AppBundle:Lead')
                ->countflag();

        $countWonThisMonthbyName = $em->getRepository('AppBundle:Lead')
                ->countwonthismonthbyname($name, 'Won');
        
        $unreadmessage = $em->getRepository('AppBundle:Message')
                ->countUnread($name);

        $countlead[] = $newcount;
        $countlead[] = $inprogress;
        $countlead[] = $flag;
        $countlead[] = $mytodo;
        $countlead[] = $mywonthismonth;
        $countlead[] = $unreadmessage;
        

        return $countlead;
    }

}
