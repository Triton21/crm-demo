<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Lead;
use AppBundle\Form\LeadType;

class TemplatelogController extends Controller {

    public function searchformAction (Request $request) {
        
        $login = $this->getUser();
        $name = $login->getUsername();
        $countlog = $this->countlog();
        
        $search = array();
        $namesearch = False;
        $emailsearch = False;
        $telsearch = False;
        $formsearch = $this->createFormBuilder($search)
                ->setAction($this->generateUrl('templatelog_search'))
                ->add('name', 'text', array(
                    'label' => 'Name',
                    'required' => false, 'attr' => array('class' => 'input-sm', 'placeholder' => 'search in log')
                ))
                ->add('id', 'text', array(
                    'label' => 'Log ID',
                    'required' => false, 'attr' => array('class' => 'input-sm', 'placeholder' => 'Log ID')
                ))
                ->add('search', 'submit', array('attr' => array('class' => 'btn-success btn-sm')))
                ->getForm();
        $formsearch->handleRequest($request);

        $lead = new Lead();
        $form = $this->createForm(new LeadType(), $lead);
        $form->handleRequest($request);
        if ($formsearch->isValid()) {
            $searchresult = $this->logsearch($formsearch);
            $namesearch = $searchresult[0];
            $emailsearch = $searchresult[1];
            $telsearch = $searchresult[2];
            $idsearch = $searchresult[3];
            return $this->render('AppBundle:Log:logsearchresult.html.twig', array('idsearch' => $idsearch, 'telsearch' => $telsearch, 'namesearch' => $namesearch, 'emailsearch' => $emailsearch, 'countlog' => $countlog, 'name' => $name,));
        }
        
        return $this->render('AppBundle:Log:templatelogsearch.html.twig', array('formsearch' => $formsearch->createView(),));
        
    }
    
    public function logsearch($formsearch) {

        $result = array();
        if ($formsearch->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $formsearch->getData();
            //if name field has item it will try to find in the databae
            if ($data['name']) {
                $em = $this->getDoctrine()->getManager();
                $namesearch = $em->getRepository('AppBundle:Telephonelog')
                        ->searchlogByName($data['name']);
                if (!$namesearch) {
                    $namesearch = '';
                }
                $result[] = $namesearch;

                //emailsearch
                $em = $this->getDoctrine()->getManager();
                $emailsearch = $em->getRepository('AppBundle:Telephonelog')
                        ->searchlogByEmail($data['name']);
                if (!$emailsearch) {
                    $emailsearch = '';
                }
                $result[] = $emailsearch;

                //tel search
                $em = $this->getDoctrine()->getManager();
                $telsearch = $em->getRepository('AppBundle:Telephonelog')
                        ->searchlogByPhone($data['name']);
                if (!$telsearch) {
                    $telsearch = '';
                }
                $result[] = $telsearch;
                $result[] = '';
            }
            if ($data['id']) {
                $em = $this->getDoctrine()->getManager();
                $idsearch = $em->getRepository('AppBundle:Telephonelog')
                        ->find($data['id']);
                $result[] = '';
                $result[] = '';
                $result[] = '';
                $result[] = $idsearch;
            }
        }
        return $result;
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
    
}