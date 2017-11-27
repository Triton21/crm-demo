<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LoginBundle\Entity\User;
use AppBundle\Entity\Userlog;
use AppBundle\Entity\Callhistory;
use AppBundle\Form\LeadType;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminController extends Controller {
    
    
    /**
     * 
     * Render the main admin page
     */
    public function adminAction() {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $em = $this->getDoctrine()->getManager();
        $alluser = $em->getRepository('LoginBundle:User')
                ->findAll();


        return $this->render('AppBundle:Admin:adminmain.html.twig', array('alluser' => $alluser, 'name' => $name,));
    }
    
    /**
     * 
     * @param Request $request
     * @param type $referencedate
     * @param type $pager
     * @return type
     */
    public function adminmonitorAction(Request $request, $referencedate, $pager) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $nowDateString = false;

        $request = $this->getRequest();
        if ($request->getMethod() == "POST") {
            $selected = $request->get("selectdate");
            $selectedDate = $selected . ' 00:00:00';
            $nowDateString = $selectedDate;
        }


        $todayString = date('Y-m-d H:i:s', mktime(0, 0, 0));
        if ($pager === 0) {
            if (!$nowDateString) {
                $nowDateString = date('Y-m-d H:i:s', mktime(0, 0, 0));
            }
            $date1start = new \DateTime($nowDateString);
            $date1finish = new \DateTime($nowDateString);
            $date2 = new \DateTime($nowDateString);
            $date3 = new \DateTime($nowDateString);
            $date4 = new \DateTime($nowDateString);
            $date5 = new \DateTime($nowDateString);
            date_modify($date1finish, '+1 day');
            date_modify($date2, '-1 day');
            date_modify($date3, '-2 day');
            date_modify($date4, '-3 day');
            date_modify($date5, '-4 day');
        }
        if ($pager === 'down') {

            $date1start = new \DateTime($referencedate);
            $date1finish = new \DateTime($referencedate);
            $date2 = new \DateTime($referencedate);
            $date3 = new \DateTime($referencedate);
            $date4 = new \DateTime($referencedate);
            $date5 = new \DateTime($referencedate);
            date_modify($date1finish, '+1 day');
            date_modify($date2, '-1 day');
            date_modify($date3, '-2 day');
            date_modify($date4, '-3 day');
            date_modify($date5, '-4 day');
            date_modify($date1start, '-5 day');
            date_modify($date1finish, '-5 day');
            date_modify($date2, '-5 day');
            date_modify($date3, '-5 day');
            date_modify($date4, '-5 day');
            date_modify($date5, '-5 day');
            $nowDateString = $date1start->format('Y-m-d H:i:s');
        }
        if ($pager === 'up') {

            $date1start = new \DateTime($referencedate);
            $date1finish = new \DateTime($referencedate);
            $date2 = new \DateTime($referencedate);
            $date3 = new \DateTime($referencedate);
            $date4 = new \DateTime($referencedate);
            $date5 = new \DateTime($referencedate);
            date_modify($date1finish, '+1 day');
            date_modify($date2, '-1 day');
            date_modify($date3, '-2 day');
            date_modify($date4, '-3 day');
            date_modify($date5, '-4 day');
            date_modify($date1start, '+5 day');
            date_modify($date1finish, '+5 day');
            date_modify($date2, '+5 day');
            date_modify($date3, '+5 day');
            date_modify($date4, '+5 day');
            date_modify($date5, '+5 day');
            $nowDateString = $date1start->format('Y-m-d H:i:s');
        }

        $usermonitor1 = $em->getRepository('AppBundle:Usermonitor')
                ->findactivity($date1start, $date1finish);
        $usermonitor2 = $em->getRepository('AppBundle:Usermonitor')
                ->findactivity($date2, $date1start);
        $usermonitor3 = $em->getRepository('AppBundle:Usermonitor')
                ->findactivity($date3, $date2);
        $usermonitor4 = $em->getRepository('AppBundle:Usermonitor')
                ->findactivity($date4, $date3);
        $usermonitor5 = $em->getRepository('AppBundle:Usermonitor')
                ->findactivity($date5, $date4);

        return $this->render('AppBundle:Admin:adminmonitor.html.twig', array(
                    'todayString' => $todayString, 'nowDateString' => $nowDateString, 'date2' => $date2, 'date3' => $date3, 'date4' => $date4, 'date5' => $date5, 'date1start' => $date1start, 'usermonitor1' => $usermonitor1, 'usermonitor2' => $usermonitor2, 'usermonitor3' => $usermonitor3, 'usermonitor4' => $usermonitor4, 'usermonitor5' => $usermonitor5, 'name' => $name,));
    }

    public function adminworklogAction(Request $request, $username, $todayString, $pager) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        if ($todayString === 'nan') {
            $todayString = date('d-m-Y H:i:s', mktime(0, 0, 0));
        }

        $findusers = $em->getRepository('LoginBundle:User')->findAll();
        $allusersarray = array(0 => ' ');
        foreach ($findusers as $us) {
            $allusersarray[$us->getUsername()] = $us->getUsername();
        }
        $to_remove = array('admin');
        $usersarray = array_diff($allusersarray, $to_remove);
        if ($request->getMethod() == "POST") {
            $selected = $request->get("selectdate");
            if ($selected) {
                $selectedDate = $selected . ' 00:00:00';
                $todayString = $selectedDate;
            }
        }
        $date1start = new \DateTime($todayString);
        $date1finish = new \DateTime($todayString);
        date_modify($date1finish, '+1 day');
        
        if($pager === 'up') {
            date_modify($date1start, '+1 day');
            date_modify($date1finish, '+1 day');
            $todayString = $date1start->format('Y-m-d H:i:s');
        }
        if($pager === 'down') {
            date_modify($date1start, '-1 day');
            date_modify($date1finish, '-1 day');
            $todayString = $date1start->format('Y-m-d H:i:s');
        }

        $userworklog = $em->getRepository('AppBundle:Userlog')
                ->findactivity($date1start, $date1finish, $username);

        return $this->render('AppBundle:Admin:adminworklog.html.twig', array(
                    'usersarray' => $usersarray, 'username' => $username, 'userworklog' => $userworklog, 'name' => $name, 'todayString' => $todayString,
        ));
    }

}
