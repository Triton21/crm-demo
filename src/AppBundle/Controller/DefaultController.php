<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Lead;
use AppBundle\Entity\Callhistory;
use AppBundle\Entity\Alarm;
use AppBundle\Entity\Textmessage;
use AppBundle\Entity\Usermonitor;
use AppBundle\Entity\Userlog;
use AppBundle\Entity\Telephonelog;
use AppBundle\Entity\Maininbox;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Paginator\Paginator;
use Symfony\Component\Yaml\Yaml;
use Twilio\Rest\Client;

class DefaultController extends Controller {

    public function logoutregisterAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $userId = $login->getId();
        $em = $this->getDoctrine()->getManager();
        $usermonitor = new Usermonitor();
        $usermonitor->setUserId($userId);
        $usermonitor->setUsername($name);
        $usermonitor->setLogtype('logout');
        $usermonitor->setCreatedAt(new \DateTime());
        $em->persist($usermonitor);
        $em->flush();
        return $this->redirectToRoute('logout');
    }

    public function autotimeoutAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $userId = $login->getId();
        $em = $this->getDoctrine()->getManager();
        $usermonitor = new Usermonitor();
        $usermonitor->setUserId($userId);
        $usermonitor->setUsername($name);
        $usermonitor->setLogtype('timeout');
        $usermonitor->setCreatedAt(new \DateTime());
        $em->persist($usermonitor);
        $em->flush();

        return $this->redirectToRoute('logout');
    }

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

    public function loginregisterAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $userip = $_SERVER['REMOTE_ADDR'];
        $userId = $login->getId();
        $em = $this->getDoctrine()->getManager();
        $usermonitor = new Usermonitor();
        $usermonitor->setUserId($userId);
        $usermonitor->setUsername($name);
        $usermonitor->setLogtype('login');
        $usermonitor->setUserip($userip);
        $usermonitor->setCreatedAt(new \DateTime());
        $em->persist($usermonitor);
        $em->flush();

        $newMessageCount = $em->getRepository('AppBundle:Message')
                ->countUnread($name);
        $unsolvedtaskCount = $em->getRepository('AppBundle:Telephonelog')
                ->countmyunsolved('0', $name);

        if ($newMessageCount != '0' || $unsolvedtaskCount != '0') {
            return $this->redirectToRoute('lead_loginmodal');
        }

        return $this->redirectToRoute('lead_managermain');
    }

    public function ajaxcheckloginmodalAction() {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $newMessageCount = $em->getRepository('AppBundle:Message')
                ->countUnread($name);

        $unsolvedtaskCount = $em->getRepository('AppBundle:Telephonelog')
                ->countmyunsolved('0', $name);

        if ($newMessageCount != '0' || $unsolvedtaskCount != '0') {
            $html = $this->renderView('AppBundle:Default:ajaxchecklogin.html.twig', array('message' => $newMessageCount, 'unsolved' => $unsolvedtaskCount,));
        } else {
            $html = 'X';
        }
        $response = new JsonResponse($html);
        return $response;
    }

    public function leadloginmodalAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $this->userLogRegisterAction('Lead main opened');

        $countlead = $this->countlead();
        $countlog = $this->countlog();

        $myNotReachedDates = $this->notreacheddate(0);
        $startdate = $myNotReachedDates['0'];
        $finishdate = $myNotReachedDates['1'];

        $myNotReachedDatesTwo = $this->notreacheddate(1);
        $startdateTwo = $myNotReachedDatesTwo['0'];
        $finishdateTwo = $myNotReachedDatesTwo['1'];

        $myNotReachedDatesThis = $this->thisweekdates();
        $startdatethis = $myNotReachedDatesThis['0'];
        $finishdatethis = $myNotReachedDatesThis['1'];

        $countthisweekarray = $em->getRepository('AppBundle:Lead')
                ->countNotReachedLastWeek($startdatethis, $finishdatethis);
        $countthisweek = $countthisweekarray['1'];

        $countlastweekarray = $em->getRepository('AppBundle:Lead')
                ->countNotReachedLastWeek($startdate, $finishdate);
        $countlastweek = $countlastweekarray['1'];

        $countlastweekarrayTwo = $em->getRepository('AppBundle:Lead')
                ->countNotReachedLastWeek($startdateTwo, $finishdateTwo);
        $countlastweekTwo = $countlastweekarrayTwo['1'];

        return $this->render('AppBundle:Default:leadloginmodal.html.twig', array(
                    'countlog' => $countlog, 'countthisweek' => $countthisweek, 'countlastweekTwo' => $countlastweekTwo, 'countlastweek' => $countlastweek, 'countlead' => $countlead, 'name' => $name,));
    }

    public function leadmainAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $this->userLogRegisterAction('Lead main opened');

        $countlead = $this->countlead();
        $countlog = $this->countlog();

        $myNotReachedDates = $this->notreacheddate(0);
        $startdate = $myNotReachedDates['0'];
        $finishdate = $myNotReachedDates['1'];

        $myNotReachedDatesTwo = $this->notreacheddate(1);
        $startdateTwo = $myNotReachedDatesTwo['0'];
        $finishdateTwo = $myNotReachedDatesTwo['1'];

        $myNotReachedDatesThis = $this->thisweekdates();
        $startdatethis = $myNotReachedDatesThis['0'];
        $finishdatethis = $myNotReachedDatesThis['1'];

        $countthisweekarray = $em->getRepository('AppBundle:Lead')
                ->countNotReachedLastWeek($startdatethis, $finishdatethis);
        $countthisweek = $countthisweekarray['1'];

        $countlastweekarray = $em->getRepository('AppBundle:Lead')
                ->countNotReachedLastWeek($startdate, $finishdate);
        $countlastweek = $countlastweekarray['1'];

        $countlastweekarrayTwo = $em->getRepository('AppBundle:Lead')
                ->countNotReachedLastWeek($startdateTwo, $finishdateTwo);
        $countlastweekTwo = $countlastweekarrayTwo['1'];

        return $this->render('AppBundle:Default:leadmain.html.twig', array(
                    'countlog' => $countlog, 'countthisweek' => $countthisweek, 'countlastweekTwo' => $countlastweekTwo, 'countlastweek' => $countlastweek, 'countlead' => $countlead, 'name' => $name,));
    }

    public function leadrecommendedAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $this->userLogRegisterAction('Lead recommended opened');

        $countlead = $this->countlead();
        $countlog = $this->countlog();

        $myNotReachedDates = $this->notreacheddate(0);
        $startdate = $myNotReachedDates['0'];
        $finishdate = $myNotReachedDates['1'];

        $myNotReachedDatesTwo = $this->notreacheddate(1);
        $startdateTwo = $myNotReachedDatesTwo['0'];
        $finishdateTwo = $myNotReachedDatesTwo['1'];

        $myNotReachedDatesThree = $this->notreacheddate(2);
        $startdateThree = $myNotReachedDatesThree['0'];
        $finishdateThree = $myNotReachedDatesThree['1'];

        $myNotReachedDatesFour = $this->notreacheddate(3);
        $startdateFour = $myNotReachedDatesFour['0'];
        $finishdateFour = $myNotReachedDatesFour['1'];

        $myNotReachedDatesThis = $this->thisweekdates();
        $startdatethis = $myNotReachedDatesThis['0'];
        $finishdatethis = $myNotReachedDatesThis['1'];


        $countthisweekarray = $em->getRepository('AppBundle:Lead')
                ->countNotReachedLastWeek($startdatethis, $finishdatethis);
        $countthisweek = $countthisweekarray['1'];


        $countlastweekarray = $em->getRepository('AppBundle:Lead')
                ->countNotReachedLastWeek($startdate, $finishdate);
        $countlastweek = $countlastweekarray['1'];

        $countlastweekarrayTwo = $em->getRepository('AppBundle:Lead')
                ->countNotReachedLastWeek($startdateTwo, $finishdateTwo);
        $countlastweekTwo = $countlastweekarrayTwo['1'];

        $countlastweekarrayThree = $em->getRepository('AppBundle:Lead')
                ->countNotReachedLastWeek($startdateThree, $finishdateThree);
        $countlastweekThree = $countlastweekarrayThree['1'];

        $countlastweekarrayFour = $em->getRepository('AppBundle:Lead')
                ->countNotReachedLastWeek($startdateFour, $finishdateFour);
        $countlastweekFour = $countlastweekarrayFour['1'];

        return $this->render('AppBundle:Default:leadrecommended.html.twig', array(
                    'countlog' => $countlog, 'countthisweek' => $countthisweek, 'countlastweekFour' => $countlastweekFour, 'countlastweekThree' => $countlastweekThree, 'countlastweekTwo' => $countlastweekTwo, 'countlastweek' => $countlastweek, 'countlead' => $countlead, 'name' => $name,));
    }

    public function notreacheddate($weeks) {
        $myNotReachedDates = array();
        $referencedateString = '11-01-2016';
        $referencedate = new \DateTime($referencedateString);
        $nowdate = new \DateTime();
        $startdate = new \DateTime();
        $finishdate = new \DateTime();
        $interval = $referencedate->diff($nowdate);
        $daysdiff = intval($interval->format('%a'));
        $currentdayofweek = $daysdiff % 7;
        $finishInt = $currentdayofweek + ($weeks * 7);
        $startInt = $currentdayofweek + 7 + ($weeks * 7);
        date_modify($startdate, '-' . $startInt . ' day' . '00' . '00');
        date_modify($finishdate, '-' . $finishInt . ' day' . '00' . '00');
        $myNotReachedDates[] = $startdate;
        $myNotReachedDates[] = $finishdate;


        return $myNotReachedDates;
    }

    public function leadnotreachLastWeekAction() {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $this->userLogRegisterAction('Lead not reached last week opened');
        $countlead = $this->countlead();

        $paneltitle = "Leed not reached last week";

        $myNotReachedDates = $this->notreacheddate(0);
        $startdate = $myNotReachedDates['0'];
        $finishdate = $myNotReachedDates['1'];

        $notreached = $em->getRepository('AppBundle:Lead')
                ->findNotReachedLastWeek($startdate, $finishdate);

        return $this->render('AppBundle:Default:leadnotreachedlastweek.html.twig', array(
                    'paneltitle' => $paneltitle, 'countlead' => $countlead, 'notreached' => $notreached, 'name' => $name,));
    }

    public function leadnotreachedthisweekAction() {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $this->userLogRegisterAction('Lead not reached this week opened');
        $countlead = $this->countlead();

        $paneltitle = "Leed not reached this week";
        $myNotReachedDates = $this->thisweekdates();
        $startdate = $myNotReachedDates['0'];
        $finishdate = $myNotReachedDates['1'];

        $notreached = $em->getRepository('AppBundle:Lead')
                ->findNotReachedLastWeek($startdate, $finishdate);

        return $this->render('AppBundle:Default:leadnotreachedlastweek.html.twig', array(
                    'paneltitle' => $paneltitle, 'countlead' => $countlead, 'notreached' => $notreached, 'name' => $name,));
    }

    public function thisweekdates() {
        $myNotReachedDates = array();
        $referencedateString = '11-01-2016';
        $referencedate = new \DateTime($referencedateString);
        $nowdate = new \DateTime();
        $startdate = new \DateTime();
        $finishdate = new \DateTime();
        $interval = $referencedate->diff($nowdate);
        $daysdiff = intval($interval->format('%a'));
        $currentdayofweek = $daysdiff % 7;
        $startInt = $currentdayofweek;
        date_modify($startdate, '-' . $startInt . ' day' . '00' . '00');

        $myNotReachedDates[] = $startdate;
        $myNotReachedDates[] = $finishdate;

        return $myNotReachedDates;
    }

    public function leadnotreachedtwoweeksAction() {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $this->userLogRegisterAction('Lead not reached 2 weeks ago opened');
        $countlead = $this->countlead();
        $paneltitle = "Leed not reached two weeks ago";

        $myNotReachedDates = $this->notreacheddate(1);
        $startdate = $myNotReachedDates['0'];
        $finishdate = $myNotReachedDates['1'];

        $notreached = $em->getRepository('AppBundle:Lead')
                ->findNotReachedLastWeek($startdate, $finishdate);

        return $this->render('AppBundle:Default:leadnotreachedlastweek.html.twig', array(
                    'paneltitle' => $paneltitle, 'countlead' => $countlead, 'notreached' => $notreached, 'name' => $name,));
    }

    public function leadnotreachedthreeweeksAction() {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $this->userLogRegisterAction('Lead not reached 3 weeks ago opened');
        $countlead = $this->countlead();
        $paneltitle = "Leed not reached three weeks ago";

        $myNotReachedDates = $this->notreacheddate(2);
        $startdate = $myNotReachedDates['0'];
        $finishdate = $myNotReachedDates['1'];

        $notreached = $em->getRepository('AppBundle:Lead')
                ->findNotReachedLastWeek($startdate, $finishdate);

        return $this->render('AppBundle:Default:leadnotreachedlastweek.html.twig', array(
                    'paneltitle' => $paneltitle, 'countlead' => $countlead, 'notreached' => $notreached, 'name' => $name,));
    }

    public function leadnotreachedfourweeksAction() {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $this->userLogRegisterAction('Lead not reached 4 weeks ago opened');
        $countlead = $this->countlead();
        $paneltitle = "Leed not reached four weeks ago";

        $myNotReachedDates = $this->notreacheddate(3);
        $startdate = $myNotReachedDates['0'];
        $finishdate = $myNotReachedDates['1'];

        $notreached = $em->getRepository('AppBundle:Lead')
                ->findNotReachedLastWeek($startdate, $finishdate);

        return $this->render('AppBundle:Default:leadnotreachedlastweek.html.twig', array(
                    'paneltitle' => $paneltitle, 'countlead' => $countlead, 'notreached' => $notreached, 'name' => $name,));
    }

    public function leadprogressAction(Request $request, $id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        //COUNT ALL LEADS, WON, TODO(IN PROGRESS), NEW, DEAD
        $countlead = $this->countlead();

        $thislead = $em->getRepository('AppBundle:Lead')
                ->findBy(array('id' => $id));
        if (!$thislead) {
            return $this->redirectToRoute('lead_manager');
        }
        $consdatecheck = $thislead[0]->getConsdate();
        if (!$consdatecheck) {
            $consdatecheck = false;
        }

        $callhistory = $em->getRepository('AppBundle:Callhistory')
                ->findmycallhistorybyid($id);

        if ($request->getMethod() == "POST") {

            $createpost = $this->createnoteform($request);
            return $this->redirect($this->generateUrl('lead_progress', array('id' => $id)));
        }
        return $this->render('AppBundle:Default:leadprogress.html.twig', array('consdatecheck' => $consdatecheck, 'callhistory' => $callhistory, 'countlead' => $countlead, 'thislead' => $thislead, 'name' => $name,));
    }

    public function leadprogresstestAction(Request $request, $id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        //COUNT ALL LEADS, WON, TODO(IN PROGRESS), NEW, DEAD
        $countlead = $this->countlead();

        $thislead = $em->getRepository('AppBundle:Lead')
                ->findBy(array('id' => $id));
        if (!$thislead) {
            return $this->redirectToRoute('lead_manager');
        }

        return $this->render('AppBundle:Default:leadprogresstest.html.twig', array(
                    'countlead' => $countlead, 'thislead' => $thislead, 'name' => $name,));
    }

    public function ajaxprogformAction($id) {
        $em = $this->getDoctrine()->getManager();
        $callhistory = $em->getRepository('AppBundle:Callhistory')
                ->findmycallhistorybyid($id);

        $html = $this->renderView('AppBundle:Default:ajaxTodo.html.twig', array('callhistory' => $callhistory));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function leadalarmlistAction(Request $request) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();

        $em = $this->getDoctrine()->getManager();
        $thisalarm = $em->getRepository('AppBundle:Alarm')
                ->findAll();

        return $this->render('AppBundle:Default:leadalarmlist.html.twig', array(
                    'countlead' => $countlead, 'name' => $name, 'thisalarm' => $thisalarm,
        ));
    }

    public function ajaxalarmsetupAction(Request $request, $id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $message = 'Something is wrong';
        if ($request->getMethod() == "POST") {
            $aDate = $request->get("reminderdate" . $id);
            $aTime = $request->get("remindertime" . $id);
            $note = $request->get("remindernote" . $id);
            $cName = $request->get("cname" . $id);

            //$message = $aTime;


            $alarm = new Alarm();
            $alarm->setCreatedAt(new \DateTime());
            $alarm->setUser($name);
            $alarm->setCName($cName);
            $alarm->setNote($note);
            $alarm->setLeadid($id);
            $merge = $aDate . ' ' . $aTime;
            $myTime = new \DateTime($merge);
            $alarm->setAlarmAt($myTime);
            $em->persist($alarm);

            $callhistory = new Callhistory();
            $callhistory->setCalldate(new \DateTime());
            $callhistory->setNote($note . '  (Alarm time: ' . $merge . ')');
            $callhistory->setAssign($name);
            $callhistory->setStatus('alarm');
            $callhistory->setLeadid($id);
            $em->persist($callhistory);
            $em->flush();
            $message = 'Coool :)';

            $register = $cName . ' lead alarm set';
            $this->userLogRegisterAction($register);
        }

        $response = new Response(json_encode($message));
        return $response;
    }

    public function ajaxalarmsetupnewAction($id, $note, $alarmdate, $alarmtime, $cname) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();

        $message = 'Something is wrong';

        if ($alarmtime != false && $alarmdate != false) {

            $alarm = new Alarm();
            $alarm->setCreatedAt(new \DateTime());
            $alarm->setUser($name);
            $alarm->setCName($cname);
            $alarm->setNote($note);
            $alarm->setLeadid($id);
            $merge = $alarmdate . ' ' . $alarmtime;
            $myTime = new \DateTime($merge);
            $alarm->setAlarmAt($myTime);
            $em->persist($alarm);

            $callhistory = new Callhistory();
            $callhistory->setCalldate(new \DateTime());
            $callhistory->setNote($note . '  (Alarm time: ' . $merge . ')');
            $callhistory->setAssign($name);
            $callhistory->setStatus('alarm');
            $callhistory->setLeadid($id);
            $em->persist($callhistory);
            $em->flush();
            $message = 'Coool :)';

            $register = $cname . ' lead alarm set';
            $this->userLogRegisterAction($register);
        }


        $response = new Response(json_encode($message));
        return $response;
    }

    public function ajaxgetalarmAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thisalarm = $em->getRepository('AppBundle:Alarm')
                ->findOneBy(array('leadid' => $id,));
        if ($thisalarm) {
            $html = $this->renderView('AppBundle:Default:ajaxstoredAlarm.html.twig', array('thisalarm' => $thisalarm, 'id' => $id,));
        } else {
            $html = 'no alarm';
        }
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxalarmoffAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thisalarm = $em->getRepository('AppBundle:Alarm')
                ->findOneBy(array('leadid' => $id,));
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

    public function snoozealarmAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thisalarm = $em->getRepository('AppBundle:Alarm')
                ->find($id);
        if ($thisalarm) {
            $now = new \DateTime();
            $now->modify('+10 minute');
            $thisalarm->setAlarmAt($now);
            $em->flush();
            $response = new Response(json_encode('Successfully snoozed'));
        } else {
            $response = new Response(json_encode('no alarm found'));
        }
        return $response;
    }

    public function turnoffalarmAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thisalarm = $em->getRepository('AppBundle:Alarm')
                ->findOneBy(array('id' => $id,));
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

    public function visitturnoffalarmAction($id) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $thisalarm = $em->getRepository('AppBundle:Alarm')
                ->findOneBy(array('id' => $id,));
        if ($thisalarm) {
            $leadid = $thisalarm->getLeadid();
            $em->remove($thisalarm);
            $em->flush();
            $html = 'delete successfull';
        } else {
            $html = 'no alarm';
        }
        $countlead = $this->countlead();

        $thislead = $em->getRepository('AppBundle:Lead')
                ->findBy(array('id' => $leadid));
        if (!$thislead) {
            return $this->redirectToRoute('lead_manager');
        }
        return $this->render('AppBundle:Default:leadprogresstest.html.twig', array(
                    'countlead' => $countlead, 'thislead' => $thislead, 'name' => $name,));
    }

    public function checkalarmAction() {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $fromDate = new \DateTime(); // Have for example 2013-06-10 09:53:21
        $fromDate->modify('+5 minute'); // Have 2013-06-11 00:00:00
        $thisalarm = $em->getRepository('AppBundle:Alarm')
                ->findalarm($name, $fromDate);
        if ($thisalarm) {
            $leadid = $thisalarm->getLeadid();
            $logid = $thisalarm->getLogid();
            if ($leadid) {
                $alarmId = $thisalarm->getId();
                $html = $this->renderView('AppBundle:Default:ajaxshowalarm.html.twig', array('thisalarm' => $thisalarm));
                $respArray = array();
                $respArray['alarmid'] = $alarmId;
                $respArray['alarm'] = $html;
                $response = new JsonResponse($respArray);
            }
            if ($logid) {
                $alarmId = $thisalarm->getId();
                $html = $this->renderView('AppBundle:Default:ajaxshowlogalarm.html.twig', array('thisalarm' => $thisalarm));
                $respArray = array();
                $respArray['alarmid'] = $alarmId;
                $respArray['alarm'] = $html;
                $response = new JsonResponse($respArray);
            }
        } else {
            $html = 'X';
            $response = new Response(json_encode($html));
        }
        return $response;
    }

    public function allleadAction(Request $request, $page, $itemperpage) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();
        $offset = ($page * $itemperpage) - $itemperpage;

        $em = $this->getDoctrine()->getManager();
        $allleadraw = $em->getRepository('AppBundle:Lead')
                ->findAllbyPage($offset, $itemperpage);

        if ($request->getMethod() == "POST") {
            $getid = $request->get("assign");
            if ($getid) {
                $createpost = $this->createnoteform($request);
                return $this->redirect($this->generateUrl('lead_progress', array('id' => $getid)));
            }
        }

        //paginator and filter
        $todolenght = $em->getRepository('AppBundle:Lead')
                ->countall();
        $pages = ceil($todolenght / $itemperpage);
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

        $alllead = $allleadraw;

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
        return $this->render('AppBundle:Default:alllead.html.twig', array('pagedown' => $pagedown, 'pageup' => $pageup, 'itemperpage' => $itemperpage, 'page' => $page, 'pagesarray' => $pagesarray, 'countlead' => $countlead, 'alllead' => $alllead, 'name' => $name,));
    }

    public function pendingAction(Request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();

        $em = $this->getDoctrine()->getManager();
        $pendingleadarray = $em->getRepository('AppBundle:Lead')
                ->findleadsbystatus('Pending');
        $pendingleads = array_reverse($pendingleadarray);

        //$Request = $this->getRequest();

        if ($request->getMethod() == "POST") {
            $getid = $request->get("assign");
            $createpost = $this->createnoteform($request);
            return $this->redirect($this->generateUrl('lead_progress', array('id' => $getid)));
        }

        return $this->render('AppBundle:Default:pending.html.twig', array('countlead' => $countlead, 'alllead' => $pendingleads, 'name' => $name,));
    }

    public function myleadAction(Request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();
        $this->userLogRegisterAction('Lead my lead opened');

        // find todo leads and call history with array
        $em = $this->getDoctrine()->getManager();
        $todoleadarray = $em->getRepository('AppBundle:Lead')
                ->findmytodobyname($name);
        $todoleads = array_reverse($todoleadarray);

        return $this->render('AppBundle:Default:myleads.html.twig', array('countlead' => $countlead, 'myleads' => $todoleads, 'name' => $name,));
    }

    public function leadtestAction(Request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();

        // find todo leads and call history with array
        $em = $this->getDoctrine()->getManager();
        $todoleadarray = $em->getRepository('AppBundle:Lead')
                ->findmytodobyname($name);
        $todoleads = array_reverse($todoleadarray);

        return $this->render('AppBundle:Default:leadtest.html.twig', array('countlead' => $countlead, 'myleads' => $todoleads, 'name' => $name,));
    }

    public function todoAction(Request $request, $page, $itemperpage, $sort1, $sort2, $myfilter) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();
        // find todo leads and call history with array
        $em = $this->getDoctrine()->getManager();

        $pageslenghtarray = $em->getRepository('AppBundle:Lead')
                ->countStatusByFilter('In progress', $myfilter);
        $pageslenght = $pageslenghtarray[0][1];
        $limit = $itemperpage;

        if ($sort1 === '1') {
            $offset = ($page * $limit) - $limit;
            $todoleads = $em->getRepository('AppBundle:Lead')
                    ->findLeadsByStatusSortCreatedDesc('In progress', $offset, $limit, $myfilter);
        }
        if ($sort1 === '2') {
            $offset = ($page * $limit) - $limit;
            $todoleads = $em->getRepository('AppBundle:Lead')
                    ->findLeadsByStatusSortCreatedAsc('In progress', $offset, $limit, $myfilter);
        }

        if ($sort2 === '1') {
            $offset = ($page * $limit) - $limit;
            $todoleads = $em->getRepository('AppBundle:Lead')
                    ->findLeadsByStatusByCreatedCreatedDesc('In progress', $offset, $limit, $myfilter);
        }
        if ($sort2 === '2') {
            $offset = ($page * $limit) - $limit;
            $todoleads = $em->getRepository('AppBundle:Lead')
                    ->findLeadsByStatusByCreatedCreatedAsc('In progress', $offset, $limit, $myfilter);
        }
        if ($sort1 === '0' and $sort2 === '0') {
            $offset = ($page * $limit) - $limit;
            $todoleads = $em->getRepository('AppBundle:Lead')
                    ->findLeadsByStatuByFilterDesc('In progress', $offset, $limit, $myfilter);
        }

        //assign get this lead button form

        if ($request->getMethod() == "POST") {
            $getid = $request->get("assign");
            if ($getid) {
                $createpost = $this->createnoteform($request);
                return $this->redirect($this->generateUrl('lead_progress', array('id' => $getid)));
            }
        }

        $callhistoryraw = $em->getRepository('AppBundle:Lead')
                ->findDistinctContacted('In progress');
        $filterarrayraw = array();
        foreach ($callhistoryraw as $callraw) {
            if (strval($callraw['contacted']) != '') {
                $filterarrayraw[] = strval($callraw['contacted']);
            }
        }
        $filterarray = array_values($filterarrayraw);
        sort($filterarray);
        array_unshift($filterarray, "all");

        //paginator and filter

        $pages = ceil($pageslenght / $itemperpage);
        $pagediff = $pages - $page;
        if ($pagediff < 0) {
            $pages = 1;
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


        // last contact array
        $lastcontact = array();
        foreach ($todoleads as $todol) {
            $callid = $todol->getId();
            $lastcont = $todol->getLastcontact();
            if ($lastcont != null) {
                $lastdatestring = $lastcont->format('d-m-Y H:i:s');
                $lastcontact[$callid] = $lastdatestring;
            } else {
                $lastcontact[$callid] = 'N/A';
            }
        }


        return $this->render('AppBundle:Default:todolead.html.twig', array('lastcontact' => $lastcontact, 'myfilter' => $myfilter, 'filterarray' => $filterarray, 'pagedown' => $pagedown, 'pageup' => $pageup, 'sort1' => $sort1, 'sort2' => $sort2, 'itemperpage' => $itemperpage, 'page' => $page, 'pagesarray' => $pagesarray, 'countlead' => $countlead, 'todoleads' => $todoleads, 'name' => $name,));
    }

    public function todotestAction(Request $request, $page, $itemperpage, $sort1, $sort2, $myfilter) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();
        // find todo leads and call history with array
        $em = $this->getDoctrine()->getManager();
        $this->userLogRegisterAction('Lead in progress total opened');

        $pageslenghtarray = $em->getRepository('AppBundle:Lead')
                ->countStatusByFilter('In progress', $myfilter);
        $pageslenght = $pageslenghtarray[0][1];
        $limit = $itemperpage;

        if ($sort1 === '1') {
            $offset = ($page * $limit) - $limit;
            $todoleads = $em->getRepository('AppBundle:Lead')
                    ->findLeadsByStatusSortCreatedDesc('In progress', $offset, $limit, $myfilter);
        }
        if ($sort1 === '2') {
            $offset = ($page * $limit) - $limit;
            $todoleads = $em->getRepository('AppBundle:Lead')
                    ->findLeadsByStatusSortCreatedAsc('In progress', $offset, $limit, $myfilter);
        }

        if ($sort2 == '1') {
            $offset = ($page * $limit) - $limit;
            $todoleads = $em->getRepository('AppBundle:Lead')
                    ->findLeadsByStatusByCreatedCreatedDesc('In progress', $offset, $limit, $myfilter);
        }
        if ($sort2 == '2') {
            $offset = ($page * $limit) - $limit;
            $todoleads = $em->getRepository('AppBundle:Lead')
                    ->findLeadsByStatusByCreatedCreatedAsc('In progress', $offset, $limit, $myfilter);
        }
        if ($sort1 === '0' and $sort2 === '0') {
            $offset = ($page * $limit) - $limit;
            $todoleads = $em->getRepository('AppBundle:Lead')
                    ->findLeadsByStatuByFilterDesc('In progress', $offset, $limit, $myfilter);
        }
        //assign get this lead button form

        if ($request->getMethod() == "POST") {
            $getid = $request->get("assign");
            if ($getid) {
                $createpost = $this->createnoteform($request);
                return $this->redirect($this->generateUrl('lead_progress', array('id' => $getid)));
            }
        }


        $callhistoryraw = $em->getRepository('AppBundle:Lead')
                ->findDistinctContacted('In progress');
        $filterarrayraw = array();
        foreach ($callhistoryraw as $callraw) {
            if (strval($callraw['contacted']) != '') {
                $filterarrayraw[] = strval($callraw['contacted']);
            }
        }
        $filterarray = array_values($filterarrayraw);
        sort($filterarray);
        array_unshift($filterarray, "all");

        //paginator and filter

        $pages = ceil($pageslenght / $itemperpage);
        $pagediff = $pages - $page;
        if ($pagediff < 0) {
            $pages = 1;
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


        // last contact array
        $lastcontact = array();
        foreach ($todoleads as $todol) {
            $callid = $todol->getId();
            $lastcont = $todol->getLastcontact();
            if ($lastcont != null) {
                $lastdatestring = $lastcont->format('d-m-Y H:i:s');
                $lastcontact[$callid] = $lastdatestring;
            } else {
                $lastcontact[$callid] = 'N/A';
            }
        }

        return $this->render('AppBundle:Default:todotest.html.twig', array('lastcontact' => $lastcontact, 'myfilter' => $myfilter, 'filterarray' => $filterarray, 'pagedown' => $pagedown, 'pageup' => $pageup, 'sort1' => $sort1, 'sort2' => $sort2, 'itemperpage' => $itemperpage, 'page' => $page, 'pagesarray' => $pagesarray, 'countlead' => $countlead, 'todoleads' => $todoleads, 'name' => $name,));
    }

    public function ajaxTodoAction($id) {
        $em = $this->getDoctrine()->getManager();
        $login = $this->getUser();
        $name = $login->getUsername();
        $thislead = $em->getRepository('AppBundle:Lead')
                ->findBy(array('id' => $id));
        if (!$thislead) {
            return $this->redirectToRoute('lead_manager');
        }
        $consdatecheck = $thislead[0]->getConsdate();
        if (!$consdatecheck) {
            $consdatecheck = false;
        }

        $customerName = $thislead[0]->getCustomerName();
        $register = $customerName . ' lead opened';
        $this->userLogRegisterAction($register);

        $callhistory = $em->getRepository('AppBundle:Callhistory')
                ->findmycallhistorybyid($id);
        $html = $this->renderView('AppBundle:Default:ajaxTodo.html.twig', array('consdatecheck' => $consdatecheck, 'thislead' => $thislead, 'callhistory' => $callhistory, 'name' => $name,));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxPostFormAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $login = $this->getUser();
        $name = $login->getUsername();
        $message = 'Something is wrong';
        if ($request->getMethod() == "POST") {
            $newStatus = $request->get("newstatus");
            $newNote = $request->get("notestatus");
            $newProb = $request->get("newprobab");
            if ($newProb) {
                $thislead = $em->getRepository('AppBundle:Lead')
                        ->findOneBy(array('id' => $id));
                $thislead->setProbability($newProb);
                $thislead->setAssign($name);
                $em->flush();
                //User register
                $customerName = $thislead->getCustomerName();
                $register = $customerName . ' probability has been set';
                $this->userLogRegisterAction($register);

                $message = 'Prob success';
            }
            if ($newNote) {
                $note = $this->createnoteform($request);
                $thislead = $em->getRepository('AppBundle:Lead')
                        ->findOneBy(array('id' => $id));
                $thislead->setAssign($name);
                $em->flush();
                //User register
                $customerName = $thislead->getCustomerName();
                $noteContent = $request->get("note");
                $register = $customerName . ' Note: ' . $noteContent;
                $this->userLogRegisterAction($register);

                $message = $newNote;
            }
            if ($newStatus) {
                $thislead = $em->getRepository('AppBundle:Lead')
                        ->findOneBy(array('id' => $id));
                $thislead->setStatus($newStatus);
                $thislead->setAssign($name);
                $em->flush();

                $customerName = $thislead->getCustomerName();
                $register = $customerName . ' Status changed to: ' . $newStatus;
                $this->userLogRegisterAction($register);

                $message = 'Success';
            }
        }
        $response = new Response(json_encode($message));
        return $response;
    }

    public function ajaxupdatevaluesAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thislead = $em->getRepository('AppBundle:Lead')
                ->findOneBy(array('id' => $id));
        $message = $thislead->getStatus();
        $response = new Response(json_encode($message));
        return $response;
    }

    public function ajaxupdatecontactedAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thislead = $em->getRepository('AppBundle:Lead')
                ->findOneBy(array('id' => $id));
        $html = $this->renderView('AppBundle:Default:ajaxupdatecontacted.html.twig', array('thislead' => $thislead,));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxtextmessageAction($id) {
        $em = $this->getDoctrine()->getManager();
        $templateArray = [];
        $thislead = $em->getRepository('AppBundle:Lead')
                ->findOneBy(array('id' => $id));
        $phone = $thislead->getCustomerTel();
        $textTemplate = $em->getRepository('AppBundle:Texttemplate')
                ->findTemplates();

        foreach ($textTemplate as $temp) {
            $templateArray[$temp->getId()] = $temp->getTemplateName();
        }

        $html = $this->renderView('AppBundle:Default:ajaxTextMessageform.html.twig', array(
            'id' => $id,
            'phone' => $phone,
            'thislead' => $thislead,
            'templateArray' => $templateArray,));

        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxgetTextTemplateAction($id, $userId) {
        $em = $this->getDoctrine()->getManager();
        $mytemplate = $em->getRepository('AppBundle:Texttemplate')
                ->findOneBy(array('id' => $id));
        $mybody = $mytemplate->getTextBody();
        $thislead = false;
        if (strpos($mybody, '%firstname%') !== false) {
            $thislead = $em->getRepository('AppBundle:Lead')
                    ->findOneBy(array('id' => $userId));
            $firstname = $thislead->getFirstname();
            if (!$firstname) {
                $firstname = $thislead->getCustomerName();
            }
            $mybody = str_replace('%firstname%', $firstname, $mybody);
        } 
        
        if (strpos($mybody, '%name%') !== false) {
            if (!$thislead) {
                $thislead = $em->getRepository('AppBundle:Lead')
                        ->findOneBy(array('id' => $userId));
            }
            $customerName = $thislead->getCustomerName();
            $mybody = str_replace('%name%', $customerName, $mybody);
        }

        $response = new Response(json_encode($mybody));
        return $response;
    }

    public function ajaxtextHistoryAction($id, $page) {
        $em = $this->getDoctrine()->getManager();
        $limit = 20;
        $offset = ($limit * $page) - $limit;

        $receivedText = $em->getRepository('AppBundle:Textmessage')
                ->findById($limit, $offset, $id);
        if (!$receivedText) {
            $receivedText = false;
        }

        $countAll = $em->getRepository('AppBundle:Textmessage')
                ->countAllById($id);

        $pager = new Paginator($page, $countAll, $limit);

        $html = $this->renderView('AppBundle:Default:ajaxtextHistory.html.twig', array(
            'receivedText' => $receivedText,
            'pager' => $pager,
        ));

        //$html = 'succes text';
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxSendTextAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $phoneNumber = $request->request->get('phoneNumber');
        $textBody = $request->request->get('textBody');

        if (isset($textBody) && $textBody !== '') {
            $parameterPath = $this->container->getParameter('kernel.root_dir') . '/config/twilioParameters.yml';
            $value = Yaml::parse(file_get_contents($parameterPath));
            $sid = $value['parameters']['sid'];
            $secret = $value['parameters']['secret'];
            $accountSid = $value['parameters']['accountSid'];
            $fromNumber = '+447903576021';

            $client = new Client($sid, $secret, $accountSid);
            $client->messages->create(
                    $phoneNumber, array(
                'from' => $fromNumber,
                'body' => $textBody,
                    )
            );

            //if sent is success save to the database
            $textMessage = new Textmessage();
            $textMessage->setCreatedAt(new \DateTime());
            $textMessage->setBody($textBody);
            $textMessage->setFromNumber($fromNumber);
            $textMessage->setToNumber($phoneNumber);
            $textMessage->setLeadId($id);
            $textMessage->setMessageType(0);
            $em->persist($textMessage);
            $em->flush();

            $response = new Response(json_encode('OK'));
            return $response;
        }

        $response = new Response(json_encode('ERROR'));
        return $response;
    }

    public function ajaxemailAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thislead = $em->getRepository('AppBundle:Lead')
                ->findOneBy(array('id' => $id));
        $cname = $thislead->getCustomerName();
        $email = $thislead->getCustomerEmail();

        $settings = $em->getRepository('AppBundle:Settings')
                ->findallactive('1');
        $settingsarray = array();
        foreach ($settings as $sett) {
            $settingsarray[$sett->getId()] = $sett->getEusername();
        }

        $attachment = $em->getRepository('AppBundle:Product')
                ->findbylistname('attachment');
        $attachmentsarray = array();
        $attachmentsarray[] = '';
        foreach ($attachment as $temp) {
            $attachmentsarray[$temp->getId()] = $temp->getImageName();
        }

        $templist = $em->getRepository('AppBundle:Etemplate')->findAll();
        $tempnamearray = array();
        foreach ($templist as $temp) {
            $tempnamearray[$temp->getId()] = $temp->getTempname();
        }


        $html = $this->renderView('AppBundle:Default:ajaxemailform.html.twig', array('attachmentsarray' => $attachmentsarray, 'id' => $id, 'email' => $email, 'cname' => $cname, 'tempnamearray' => $tempnamearray, 'settingsarray' => $settingsarray, 'thislead' => $thislead,));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function sendmailajaxAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $login = $this->getUser();
        $name = $login->getUsername();

        if ($request->getMethod() == "POST") {
            $cName = $request->get("mailname");
            $email = $request->get("mailemail");
            $tempFieldName = 'tempid' . $id;
            $tempidstring = $request->get("$tempFieldName");

            $settFieldName = 'settid' . $id;
            $settIdstring = $request->get($settFieldName);
            $subjectFieldName = 'subject' . $id;
            $newsubject = $request->get($subjectFieldName);
            $textAreaName = 'editor1' . $id;
            $newbody = $request->get($textAreaName);
            $settId = intval($settIdstring);
            $tempId = intval($tempidstring);

            //find all attachment
            $thistemplate = $em->getRepository('AppBundle:Etemplate')
                    ->findOneBy(array('id' => $tempId));
            $attid1 = $thistemplate->getAttach1();
            $attid2 = $thistemplate->getAttach2();
            $attid3 = $thistemplate->getAttach3();

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

            $thissettings = $em->getRepository('AppBundle:Settings')
                    ->findOneBy(array('id' => $settId));
            $fromname = $thissettings->getFromname();
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

            $message = \Swift_Message::newInstance($newsubject)
                    ->setFrom(array($euser => $fromname))
                    ->setTo(array($email => $cName))
                    ->setBcc(array('sent@dentistabroad.co.uk' => 'Sent'))
                    ->setBody($newbody, 'text/html')
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
            } catch (Swift_TransportException $e) {
                $mailer->getTransport()->stop();
                $error = $e;
                return $this->render('AppBundle:Default:leadsendmail.html.twig', array('attachname' => $attachname, 'error' => $error, 'id' => $id, 'formsendmail' => $formsendmail->createView(), 'formsearch' => $formsearch->createView(), 'countlead' => $countlead, 'name' => $name, 'form' => $form->createView(),));
            } catch (Exception $e) {
                $mailer->getTransport()->stop();
                $error = $e;
                return $this->render('AppBundle:Default:leadsendmail.html.twig', array('attachname' => $attachname, 'error' => $error, 'id' => $id, 'formsendmail' => $formsendmail->createView(), 'formsearch' => $formsearch->createView(), 'countlead' => $countlead, 'name' => $name, 'form' => $form->createView(),));
            }

            $callhistory = new Callhistory();
            $callhistory->setLeadid($id);
            $callhistory->setCalldate(new \DateTime());
            $callhistory->setNote('Email sent: ' . $newsubject);
            $callhistory->setAssign($name);
            $callhistory->setStatus('Email');
            $em->persist($callhistory);
            $em->flush();

            //register in Mainemail
            $content = strip_tags($newbody);
            $mysent = new Maininbox();
            $mysent->setMaildate(new \DateTime());

            $mysent->setSettid($settId);
            $mysent->setFromemail($euser);
            $mysent->setFromname($fromname);
            $mysent->setSubject($newsubject);
            $mysent->setFolder('Sent');
            $mysent->setTexthtml($newbody);
            $mysent->setContent($content);
            $mysent->setToarray(array($email => $cName));
            $mysent->setToname($cName);
            $mysent->setToemail($email);
            $mysent->setUsername($name);
            $em->persist($mysent);
            $em->flush();

            //User register
            $register = $cName . ' lead email sent';
            $this->userLogRegisterAction($register);

            $html = 'Message sent!';

            $response = new Response(json_encode($html));
            return $response;
        }


        $html = 'X';
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxgettemplateAction($id) {
        $em = $this->getDoctrine()->getManager();
        $mytemplate = $em->getRepository('AppBundle:Etemplate')
                ->findOneBy(array('id' => $id));
        $mysubject = $mytemplate->getSubject();
        $mybody = $mytemplate->getBody();

        $templateArray = array();
        $templateArray['subject'] = $mysubject;
        $templateArray['body'] = $mybody;
        $response = new JsonResponse($templateArray);
        return $response;
    }

    public function leadajaxattachAction($id) {
        $em = $this->getDoctrine()->getManager();
        $attname1 = false;
        $attname2 = false;
        $attname3 = false;

        $ctemplate = $em->getRepository('AppBundle:Etemplate')
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
        $html = $this->renderView('AppBundle:Default:confirmajaxtemplate.html.twig', array('attname1' => $attname1, 'attname2' => $attname2, 'attname3' => $attname3));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function ajaxupdateprobabilityAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thislead = $em->getRepository('AppBundle:Lead')
                ->findOneBy(array('id' => $id));
        $newProbability = $thislead->getProbability();
        $response = new Response(json_encode($newProbability));
        return $response;
    }

    public function ajaxLeadProgressAction($id) {
        $em = $this->getDoctrine()->getManager();
        $login = $this->getUser();
        $name = $login->getUsername();
        $thislead = $em->getRepository('AppBundle:Lead')
                ->findBy(array('id' => $id));
        if (!$thislead) {
            return $this->redirectToRoute('lead_manager');
        }
        $consdatecheck = $thislead[0]->getConsdate();
        if (!$consdatecheck) {
            $consdatecheck = false;
        }
        //User register
        $customerName = $thislead[0]->getCustomerName();
        $register = $customerName . ' lead opened';
        $this->userLogRegisterAction($register);

        $callhistory = $em->getRepository('AppBundle:Callhistory')
                ->findmycallhistorybyid($id);
        $html = $this->renderView('AppBundle:Default:ajaxLeadProgress.html.twig', array('consdatecheck' => $consdatecheck, 'thislead' => $thislead, 'callhistory' => $callhistory, 'name' => $name,));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function newAction(Request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();

        $em = $this->getDoctrine()->getManager();
        $newleadarray = $em->getRepository('AppBundle:Lead')
                ->findleadsbystatus('new');
        $newleads = array_reverse($newleadarray);

        //assign get this lead button form

        if ($request->getMethod() == "POST") {
            $getid = $request->get("assign");
            $createpost = $this->createnoteform($request);
            return $this->redirect($this->generateUrl('lead_progress', array('id' => $getid)));
        }

        return $this->render('AppBundle:Default:newlead.html.twig', array('countlead' => $countlead, 'newlead' => $newleads, 'name' => $name,));
    }

    public function newajaxAction() {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();

        $em = $this->getDoctrine()->getManager();
        $newleadarray = $em->getRepository('AppBundle:Lead')
                ->findleadsbystatus('new');


        $newleads = array_reverse($newleadarray);

        return $this->render('AppBundle:Default:newajax.html.twig', array('countlead' => $countlead, 'newlead' => $newleads, 'name' => $name,));
    }

    public function mywonAction(Request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();

        $em = $this->getDoctrine()->getManager();
        $mywonarray = $em->getRepository('AppBundle:Lead')
                ->findmyleadsbystatus($name, 'Won');
        $mywonlead = array_reverse($mywonarray);

        return $this->render('AppBundle:Default:mywon.html.twig', array('countlead' => $countlead, 'mywonleads' => $mywonlead, 'name' => $name,));
    }

    public function flagAction(Request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();

        $em = $this->getDoctrine()->getManager();
        $flagarray = $em->getRepository('AppBundle:Lead')
                ->findBy(array('flag' => '1', 'assign' => $name));
        $flag = array_reverse($flagarray);

        return $this->render('AppBundle:Default:flag.html.twig', array('countlead' => $countlead, 'flag' => $flag, 'name' => $name,));
    }

    public function reminderAction(Request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();

        $em = $this->getDoctrine()->getManager();
        $alertrarray = $em->getRepository('AppBundle:Lead')
                ->findalertreminder();
        $alert = array_reverse($alertrarray);

        $em = $this->getDoctrine()->getManager();
        $reminderarray = $em->getRepository('AppBundle:Lead')
                ->findallreminder();
        $reminder = array_reverse($reminderarray);

        return $this->render('AppBundle:Default:reminder.html.twig', array('countlead' => $countlead, 'alert' => $alert, 'reminder' => $reminder, 'name' => $name,));
    }

    public function allwonAction($page) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();
        $em = $this->getDoctrine()->getManager();

        $limit = 20;
        $offset = ($limit * $page) - $limit;

        $allwonlead = $em->getRepository('AppBundle:Lead')
                ->findLeadsByStatusByCreatedCreatedDesc('Won', $offset, $limit, 'all');
        $countAll = $em->getRepository('AppBundle:Lead')
                ->countLeadByStatus('Won');
        $pager = new Paginator($page, $countAll, $limit);
        return $this->render('AppBundle:Default:allwon.html.twig', array(
                    'pager' => $pager, 'countlead' => $countlead, 'allwonlead' => $allwonlead, 'name' => $name,));
    }

    public function statAction(Request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();
        $countstatarray = $this->statistic();
        $countstat = $countstatarray[0];
        $uniquemonth = $countstatarray[1];
        $resultarray = $countstatarray[2];
        return $this->render('AppBundle:Default:stat.html.twig', array('resultarray' => $resultarray, 'uniquemonth' => $uniquemonth, 'countstat' => $countstat, 'countlead' => $countlead, 'name' => $name,));
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

    public function statistic() {
        //COUNT ALL LEADS, WON, TODO(IN PROGRESS), NEW, DEAD
        $login = $this->getUser();
        $name = $login->getUsername();

        $countstat = array();
        $em = $this->getDoctrine()->getManager();

        $mycallthismonth = $em->getRepository('AppBundle:Callhistory')
                ->countCallsThisMonthbyName($name, 'Called');

        $mycall = $em->getRepository('AppBundle:Callhistory')
                ->countCallbyName($name, 'Called');

        $mynotreachedthismonth = $em->getRepository('AppBundle:Callhistory')
                ->countCallsThisMonthbyName($name, 'Could not reach');

        $mynotreached = $em->getRepository('AppBundle:Callhistory')
                ->countCallbyName($name, 'Could not reach');

        $mywonthismonth = $em->getRepository('AppBundle:Lead')
                ->countstatusbynamethismonth($name, 'Won');

        $mywonstatarray = $em->getRepository('AppBundle:Lead')
                ->countstatusbyname($name, 'Won');
        $mywon = $mywonstatarray[0][1];

        $myprogressthismonth = array();
        $myprogressarray = $em->getRepository('AppBundle:Lead')
                ->countstatusbyname($name, 'In progress');
        $myprogress = $myprogressarray[0][1];

        $mydeadthismonth = array();

        $mydeadarray = $em->getRepository('AppBundle:Lead')
                ->countstatusbyname($name, 'Dead');
        $mydead = $mydeadarray[0][1];
        $callthismonth = $em->getRepository('AppBundle:Callhistory')
                ->countCallbyStatus('Called');

        $call = $em->getRepository('AppBundle:Callhistory')
                ->countCallbyStatus('Called');

        $notreachedthismonth = $em->getRepository('AppBundle:Callhistory')
                ->countCallbyStatus('Could not reach');

        $notreached = $em->getRepository('AppBundle:Callhistory')
                ->countCallbyStatus('Could not reach');

        $allwonthismonth = $em->getRepository('AppBundle:Lead')
                ->countstatusthismonth('Won');

        $allwon = $em->getRepository('AppBundle:Lead')
                ->countstatus('Won');

        $alltodothismonth = array();

        $alltodo = $em->getRepository('AppBundle:Lead')
                ->countstatus('In progress');

        $alldeadthismonth = array();

        $alldead = $em->getRepository('AppBundle:Lead')
                ->countstatus('Dead');

        $countstat[] = $mycallthismonth;
        $countstat[] = $mycall;
        $countstat[] = $mynotreachedthismonth;
        $countstat[] = $mynotreached;
        $countstat[] = $mywonthismonth;
        $countstat[] = $mywon;
        $countstat[] = $myprogressthismonth;
        $countstat[] = $myprogress;
        $countstat[] = $mydeadthismonth;
        $countstat[] = $mydead;
        $countstat[] = $callthismonth;
        $countstat[] = $call;
        $countstat[] = $notreachedthismonth;
        $countstat[] = $notreached;
        $countstat[] = $allwonthismonth;
        $countstat[] = $allwon;
        $countstat[] = $alltodothismonth;
        $countstat[] = $alltodo;
        $countstat[] = $alldeadthismonth;
        $countstat[] = $alldead;

        $uniquemonth = false;
        $resultarray = false;
        //find the unique month where was any won for the name
        $mywondatearray = array();
        $mywonarray = $em->getRepository('AppBundle:Lead')
                ->findmyleadsbystatus($name, 'Won');
        if ($mywonarray) {
            foreach ($mywonarray as $myw) {
                $mywondatearray[] = $myw->getWondate();
            }
            $mymontharray = array();
            foreach ($mywondatearray as $mym) {
                $newdate = $mym->format('F');
                $mymontharray[] = $newdate;
            }
            $uniquemonth = array_unique($mymontharray);
            $myresult = $em->getRepository('AppBundle:Lead')
                    ->findmyleadsbystatus($name, 'Won');
            $counter = 0;
            $resultarray = array();
            foreach ($uniquemonth as $um) {
                foreach ($myresult as $myres) {
                    $wondate = $myres->getWondate();
                    $newwondate = $wondate->format('F');
                    if ($newwondate == $um) {
                        $counter++;
                    }
                }
                $resultarray[$um] = $counter;
                $counter = 0;
            }
        }
        return array($countstat, $uniquemonth, $resultarray);
    }

    public function createnoteform($request) {
        $login = $this->getUser();
        $name = $login->getUsername();

        if ($request->getMethod() == "POST") {

            //get button form for new leads
            $getid = $request->get("assign");
            if ($getid) {
                $em = $this->getDoctrine()->getManager();
                $lead = $em->getRepository('AppBundle:Lead')->findOneBy(array('id' => $getid));
                $lead->setAssign($name);
                $em->flush();
                return $getid;
            }

            //change status form
            $status = $request->get("status");
            $statusid = $request->get("statusid");
            $flagged = $request->get("flag");
            if ($status) {
                $em = $this->getDoctrine()->getManager();
                $lead = $em->getRepository('AppBundle:Lead')->findOneBy(array('id' => $statusid));
                if ($status === 'In progress') {
                    $lead->setStatus($status);
                    if ($flagged === 'flag') {
                        $lead->setFlag('1');
                    } else {
                        $lead->setFlag(null);
                    }
                    $em->flush();
                }
                if ($status === 'new') {
                    $lead->setStatus($status);
                    if ($flagged === 'flag') {
                        $lead->setFlag('1');
                    } else {
                        $lead->setFlag(null);
                    }
                    $em->flush();
                }
                if ($status === 'x') {
                    if ($flagged === 'flag') {
                        $lead->setFlag('1');
                    } else {
                        $lead->setFlag(null);
                    }
                    $em->flush();
                }

                if ($status === 'Won') {
                    $lead->setWondate(new \DateTime());
                    $lead->setStatus($status);
                    if ($flagged === 'flag') {
                        $lead->setFlag('1');
                    } else {
                        $lead->setFlag(null);
                    }
                    $callhistory = new Callhistory();
                    $callhistory->setLeadid($statusid);
                    $callhistory->setCalldate(new \DateTime());
                    $callhistory->setNote('Booked for consultation');
                    $callhistory->setAssign($name);
                    $callhistory->setStatus('Won');
                    $callhistory->setLead($lead);
                    $em->persist($callhistory);
                    $em->flush();
                }

                if ($status === 'Dead') {
                    $lead->setStatus($status);
                    if ($flagged === 'flag') {
                        $lead->setFlag('1');
                    } else {
                        $lead->setFlag(null);
                    }
                    $callhistory = new Callhistory();
                    $callhistory->setLeadid($statusid);
                    $callhistory->setCalldate(new \DateTime());
                    $callhistory->setNote('Set as dead');
                    $callhistory->setAssign($name);
                    $callhistory->setStatus('Dead');
                    $callhistory->setLead($lead);
                    $em->persist($callhistory);
                    $em->persist($lead);
                    $em->flush();
                }

                if ($status === 'Not eligible') {
                    $lead->setWondate(new \DateTime());
                    $lead->setStatus($status);
                    if ($flagged === 'flag') {
                        $lead->setFlag('1');
                    } else {
                        $lead->setFlag(null);
                    }
                    $callhistory = new Callhistory();
                    $callhistory->setLeadid($statusid);
                    $callhistory->setCalldate(new \DateTime());
                    $callhistory->setNote('Set as not eligible');
                    $callhistory->setAssign($name);
                    $callhistory->setStatus('Not eligible');
                    $callhistory->setLead($lead);

                    $em->persist($callhistory);
                    $em->flush();
                }


                if ($status === 'Pending') {
                    $lead->setWondate(new \DateTime());
                    $lead->setStatus($status);
                    if ($flagged === 'flag') {
                        $lead->setFlag('1');
                    } else {
                        $lead->setFlag(null);
                    }
                    $callhistory = new Callhistory();
                    $callhistory->setLeadid($statusid);
                    $callhistory->setCalldate(new \DateTime());
                    $callhistory->setNote('Set as pending');
                    $callhistory->setAssign($name);
                    $callhistory->setStatus('Pending');
                    $callhistory->setLead($lead);

                    $em->persist($callhistory);
                    $em->flush();
                }

                $em->flush();
            }

            //note form handling
            $noteLeadId = $request->get("leadid");
            $noteContent = $request->get("note");
            $noteStatus = $request->get("notestatus"); //to save status history
            if ($noteContent) {

                $em = $this->getDoctrine()->getManager();
                $status = 'Called';
                $addstatustolead = $this->addcallhistorytolead($noteLeadId);
                $callhistory = new Callhistory();
                $callhistory->setLeadid($noteLeadId);
                $callhistory->setCalldate(new \DateTime());
                $callhistory->setNote($noteContent);
                $callhistory->setAssign($name);
                $callhistory->setStatus($noteStatus);
                $em->persist($callhistory);
                $em->flush();
                //Telephone log register
                $lead = $em->getRepository('AppBundle:Lead')->findOneBy(array('id' => $noteLeadId));
                $lead->setLastcontact(new \DateTime());
                $contacted = $lead->getContacted();
                $newcontacted = $contacted + 1;
                $lead->setContacted($newcontacted);
                $em->flush();
                $telephonelog = new Telephonelog();
                $telephonelog->setCustomerName($lead->getCustomerName());
                $telephonelog->setCreatedAt(new \DateTime());
                $telephonelog->setCustomerEmail($lead->getCustomerEmail());
                $telephonelog->setCustomerTel($lead->getCustomerTel());
                $telephonelog->setNote($noteContent);
                $telephonelog->setAssign($name);
                $telephonelog->setLeadid($noteLeadId);
                $telephonelog->setSolved('1');
                $telephonelog->setInorout('out');
                $em->persist($telephonelog);
                $em->flush();
            }

            if ($noteStatus === 'Could not reach') {
                $em = $this->getDoctrine()->getManager();
                $status = 'Could not reach';
                $callhistory = new Callhistory();
                $callhistory->setLeadid($noteLeadId);
                $callhistory->setCalldate(new \DateTime());
                $callhistory->setNote('Could not reach');
                $callhistory->setAssign($name);
                $callhistory->setStatus($noteStatus);
                $em->persist($callhistory);
                $em->flush();
            }

            //datepicker persist date to both database
            $consultationdate = $request->get("consdate");
            $consLeadId = $request->get("leadid");
            if ($consultationdate) {
                $converteddate = date('m/d/y', strtotime($consultationdate));

                $em = $this->getDoctrine()->getManager();
                $callhistory = new Callhistory();
                $callhistory->setLeadid($noteLeadId);
                $callhistory->setCalldate(new \DateTime());
                $callhistory->setNote('Consultation date settled');
                $callhistory->setAssign($name);
                $callhistory->setStatus('Won');
                $callhistory->setConsdate(new \DateTime($converteddate));
                $em->persist($callhistory);
                $em->flush();

                $em = $this->getDoctrine()->getManager();
                $lead = $em->getRepository('AppBundle:Lead')->findOneBy(array('id' => $consLeadId));
                $lead->setConsdate(new \DateTime($converteddate));
                $em->flush();
            }

            //reminder form handling
            $remindernote = $request->get("remindernote");
            $reminderdate = $request->get("reminderdate");
            $reminderid = $request->get("id");
            if ($remindernote) {
                $em = $this->getDoctrine()->getManager();
                $lead = $em->getRepository('AppBundle:Lead')->findOneBy(array('id' => $reminderid));
                $lead->setRemindernote($remindernote);
                $em->flush();
            }
            if ($reminderdate) {
                $em = $this->getDoctrine()->getManager();
                $lead = $em->getRepository('AppBundle:Lead')->findOneBy(array('id' => $reminderid));
                $lead->setReminder(new \DateTime($reminderdate));
                $em->flush();
            }
            $deletereminderid = $request->get("deletereminderid");
            if ($deletereminderid) {
                $em = $this->getDoctrine()->getManager();
                $lead = $em->getRepository('AppBundle:Lead')->findOneBy(array('id' => $deletereminderid));
                $lead->setReminder(null);
                $lead->setRemindernote(null);
                $em->flush();
            }
            return;
        }
    }

    public function addcallhistorytolead($statusid) {
        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $leadstatus = $em->getRepository('AppBundle:Lead')->findOneBy(array('id' => $statusid));
        $status = 'Called';
        if ($status === 'Called') {
            $called1status = $leadstatus->getCalled1();
            if (!$called1status) {
                $leadstatus->setCalled1(new \DateTime());
            } else {
                $called2status = $leadstatus->getCalled2();
                if (!$called2status) {
                    $leadstatus->setCalled2(new \DateTime());
                } else {
                    $called3status = $leadstatus->getCalled3();
                    if (!$called3status) {
                        $leadstatus->setCalled3(new \DateTime());
                    } else {
                        $called4status = $leadstatus->getCalled4();
                        if (!$called4status) {
                            $leadstatus->setCalled4(new \DateTime());
                        } else {
                            $called5status = $leadstatus->getCalled5();
                            if (!$called5status) {
                                $leadstatus->setCalled5(new \DateTime());
                            }
                        }
                    }
                }
            }
        }
        $em->flush();
        return;
    }

    public function ajaxupdatecontactdetailsAction($id) {
        $em = $this->getDoctrine()->getManager();
        $thislead = $em->getRepository('AppBundle:Lead')
                ->find($id);
        if ($thislead) {
            $html = $this->renderView('AppBundle:Default:ajaxLeadContactDetails.html.twig', array('thislead' => $thislead,));
            $response = new Response(json_encode($html));
            return $response;
        } else {
            $html = 'not found';
            $response = new Response(json_encode($html));
        }
        return $response;
    }

    public function ajaxflagAction($id) {
        $em = $this->getDoctrine()->getManager();
        $login = $this->getUser();
        $name = $login->getUsername();
        $thislead = $em->getRepository('AppBundle:Lead')
                ->find($id);
        $flag = $thislead->getFlag();
        if ($thislead) {
            $flag = $thislead->getFlag();
            if ($flag === '1') {
                $thislead->setFlag('0');
                $em->flush();
                $html = 'no flag';
                $response = new Response(json_encode($html));
                return $response;
            } else {
                $thislead->setFlag('1');
                $thislead->setAssign($name);
                $html = 1;
                $em->flush();
                $response = new Response(json_encode($html));
                return $response;
            }
            return $response;
        } else {
            $html = 'not found';
            $response = new Response(json_encode($html));
        }
        return $response;
    }

    public function leadeditajaxformAction(Request $request, $id) {
        $login = $this->getUser();
        $name = $login->getUsername();

        $em = $this->getDoctrine()->getManager();
        $oldlead = $em->getRepository('AppBundle:Lead')->find($id);
        $oldname = $oldlead->getCustomerName();
        $oldfirstname = $oldlead->getFirstname();
        $oldsurname = $oldlead->getSurname();
        $oldemail = $oldlead->getCustomerEmail();
        $oldphone = $oldlead->getCustomerTel();
        $olddob = $oldlead->getDob();
        $lead = array();
        $form = $this->createFormBuilder($lead)
                ->add('customerName', 'text', array(
                    'label' => 'Name', 'data' => $oldname, 'attr' => array('class' => 'input-sm form-control')
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
                ->getForm();
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            $editlead = $form->getData();
            $lead = $em->getRepository('AppBundle:Lead')->find($id);
            if ($lead) {
                $lead->setCustomerName($editlead['customerName']);
                $lead->setCustomerEmail($editlead['customerEmail']);
                $lead->setCustomerTel($editlead['customerTel']);
                $lead->setFirstname($editlead['firstname']);
                $lead->setSurname($editlead['surname']);
                $lead->setDob($editlead['dob']);
                $em->flush();
                $html = 'form submitted';
                $response = new Response(json_encode($html));
                return $response;
            }
        }
        $html = $this->renderView('AppBundle:Default:leadeditajaxform.html.twig', array('form' => $form->createView(), 'id' => $id));
        $response = new Response(json_encode($html));
        return $response;
    }

    public function leadeditAction(Request $request, $id, $alert) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();

        //edit lead details form
        $em = $this->getDoctrine()->getManager();
        $oldlead = $em->getRepository('AppBundle:Lead')->find($id);
        $oldname = $oldlead->getCustomerName();
        $oldfirstname = $oldlead->getFirstname();
        $oldsurname = $oldlead->getSurname();
        $oldemail = $oldlead->getCustomerEmail();
        $oldphone = $oldlead->getCustomerTel();
        $olddob = $oldlead->getDob();

        $lead = new Lead();

        $form2 = $this->createFormBuilder($lead)
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
                ->add('save', 'submit', array(
                    'label' => 'save', 'attr' => array('class' => 'btn-success btn-md')))
                ->getForm();
        $form2->handleRequest($request);

        if ($form2->isValid()) {
            $editlead = $form2->getData();
            $lead = $em->getRepository('AppBundle:Lead')->find($id);
            if ($lead) {
                $lead->setCustomerName($editlead->getCustomerName());
                $lead->setCustomerEmail($editlead->getCustomerEmail());
                $lead->setCustomerTel($editlead->getCustomerTel());
                $lead->setFirstname($editlead->getFirstname());
                $lead->setSurname($editlead->getSurname());
                $lead->setDob($editlead->getDob());
                $em->flush();
            }

            return $this->redirectToRoute('lead_progress', array('id' => $id));
        }
        return $this->render('AppBundle:Default:edit.html.twig', array('alert' => $alert, 'countlead' => $countlead, 'name' => $name, 'form2' => $form2->createView(),));
    }

    public function helpAction(Request $request) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $countlead = $this->countlead();

        return $this->render('AppBundle:Default:help.html.twig', array('countlead' => $countlead, 'name' => $name,));
    }

    public function leadsendAction(Request $request, $id) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $sent = false;
        //COUNT ALL LEADS, WON, TODO(IN PROGRESS), NEW, DEAD
        $countlead = $this->countlead();
        $thislead = $em->getRepository('AppBundle:Lead')
                ->findOneBy(array('id' => $id));
        $thisemail = $thislead->getCustomerEmail();
        if (!$thisemail) {
            return $this->redirectToRoute('lead_edit', array('id' => $id, 'alert' => '1'));
        }

        $templist = $em->getRepository('AppBundle:Etemplate')->findAll();
        $tempnamearray = array();
        foreach ($templist as $temp) {
            $tempnamearray[$temp->getTempname()] = $temp->getTempname();
        }

        $settings = $em->getRepository('AppBundle:Settings')
                ->findallactive('1');
        $settingsarray = array();
        foreach ($settings as $temp) {
            $settingsarray[$temp->getEusername()] = $temp->getEusername();
        }

        $attachment = $em->getRepository('AppBundle:Product')
                ->findbylistname('attachment');
        $attachmentsarray = array();
        $attachmentsarray[] = '';
        foreach ($attachment as $temp) {
            $attachmentsarray[$temp->getImageName()] = $temp->getImageName();
        }

        $send = array();
        $customername = $thislead->getCustomerName();
        $customeremail = $thislead->getCustomerEmail();


        $formsend = $this->createFormBuilder($send)
                ->add('customerName', 'text', array(
                    'label' => 'Name', 'data' => $customername, 'attr' => array('class' => 'input-sm')
                ))
                ->add('customerEmail', 'email', array(
                    'label' => 'Email', 'data' => $customeremail, 'attr' => array('class' => 'input-sm')
                ))
                ->add('From', 'choice', array(
                    'label' => 'From',
                    'choices' => $settingsarray, 'attr' => array('class' => 'input-sm')
                ))
                ->add('template', 'choice', array(
                    'label' => 'Choose a template',
                    'choices' => $tempnamearray, 'attr' => array('class' => 'input-sm')
                ))
                ->add('attach', 'choice', array(
                    'label' => 'Choose aattachment',
                    'choices' => $attachmentsarray, 'attr' => array('class' => 'input-sm')
                ))
                ->add('nextcons', 'date', [
                    'widget' => 'single_text',
                    'label' => 'Next available appointment',
                    'format' => 'dd-MM-yyyy',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control input-inline datepicker',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'placeholder' => 'dd-mm-yyyy'
                    ]
                ])
                ->add('create', 'submit', array('attr' => array('class' => 'btn-success btn-sm')))
                ->getForm();
        $formsend->handleRequest($request);

        // Form mail
        if ($formsend->isValid()) {
            // create new 
            $send = $formsend->getData();
            $settname = $send['From'];
            $templatename = $send['template'];
            $attachname = $send['attach'];
            $nextconsobj = $send['nextcons'];
            if ($nextconsobj) {
                $nextcons = $nextconsobj->format('d-m-Y');
            } else {
                $nextcons = '1-1-1970';
            }
            $thissettings = $em->getRepository('AppBundle:Settings')
                    ->findOneBy(array('eusername' => $settname));
            $thistemplate = $em->getRepository('AppBundle:Etemplate')
                    ->findOneBy(array('tempname' => $templatename));

            //change {{firstname}} and {{consdate}}
            $thisattach = $em->getRepository('AppBundle:Product')
                    ->findOneBy(array('imageName' => $attachname));
            $tempid = $thistemplate->getId();
            $settid = $thissettings->getId();

            if ($thisattach) {
                $attid = $thisattach->getId();
            } else {
                $attid = 0;
            }

            return $this->redirect($this->generateUrl('lead_sendmail', array('id' => $id, 'nextcons' => $nextcons, 'tempid' => $tempid, 'settid' => $settid, 'attid' => $attid,)));
        }
        return $this->render('AppBundle:Default:leadsend.html.twig', array('sent' => $sent, 'templist' => $templist, 'thislead' => $thislead, 'formsend' => $formsend->createView(), 'countlead' => $countlead, 'name' => $name,));
    }

    public function leadsendmailAction(Request $request, $id, $nextcons, $tempid, $settid, $attid) {

        $login = $this->getUser();
        $name = $login->getUsername();
        $em = $this->getDoctrine()->getManager();
        $error = false;
        if ($nextcons == '1-1-1970') {
            $nextcons = '';
        }
        //COUNT ALL LEADS, WON, TODO(IN PROGRESS), NEW, DEAD
        $countlead = $this->countlead();

        //get all the variables from slug and find the database data
        $thislead = $em->getRepository('AppBundle:Lead')
                ->findOneBy(array('id' => $id));
        $firstname = $thislead->getFirstname();

        $thissettings = $em->getRepository('AppBundle:Settings')
                ->findOneBy(array('id' => $settid));
        $fromname = $thissettings->getFromname();
        $thistemplate = $em->getRepository('AppBundle:Etemplate')
                ->findOneBy(array('id' => $tempid));
        $tempname = $thistemplate->getTempname();
        if ($attid != 0) {
            $thisattach = $em->getRepository('AppBundle:Product')
                    ->findOneBy(array('id' => $attid));
            $attachname = $thisattach->getImageName();
        } else {
            $attachname = false;
            $thisattach = false;
        }
        $subject = $thistemplate->getSubject();
        // replace the placeholders
        $oldbody = $thistemplate->getBody();
        if (strpos($oldbody, '%firstname%') != false) {
            $newbody = str_replace('%firstname%', $firstname, $oldbody);
        } else {
            $newbody = $oldbody;
        }
        if (strpos($newbody, '%consdate%') != false) {
            $body = str_replace('%consdate%', $nextcons, $newbody);
        } else {
            $body = $newbody;
        }

        $email = $thislead->getCustomerEmail();

        $createform = array();

        $formsendmail = $this->createFormBuilder($createform)
                ->add('email', 'text', array('label' => 'Email to', 'data' => $email, 'read_only' => true))
                ->add('Subject', 'text', array(
                    'label' => 'Subject', 'data' => $subject,
                    'required' => false,
                ))
                ->add('Message', 'textarea', array(
                    'label' => 'Message', 'data' => $body,
                    'required' => false, 'attr' => array('class' => 'ckeditor')
                ))
                ->add('send', 'submit', array('attr' => array('class' => 'btn-success btn-sm')))
                ->getForm();
        $formsendmail->handleRequest($request);

        if ($formsendmail->isValid()) {

            $sendmail = $formsendmail->getData();
            $newsubject = $sendmail['Subject'];
            $newbody = $sendmail['Message'];


            $path = false;
            if ($thisattach) {
                $attName = $thisattach->getImageName();
                $attPath = $thisattach->getPath();
                $path = $attPath . $attName;
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
                $mailer = \Swift_Mailer::newInstance($transport);

                $message = \Swift_Message::newInstance($newsubject)
                        ->setFrom(array($euser => $fromname))
                        ->setTo(array($email => 'Client'))
                        ->setBcc(array('sent@dentistabroad.co.uk' => 'Sent'))
                        ->setBody($newbody, 'text/html')
                ;
                if ($path) {
                    $message->attach(\Swift_Attachment::fromPath($path));
                }

                try {
                    $mailer->getTransport()->start();
                    $mailer->send($message);
                    $mailer->getTransport()->stop();
                } catch (Swift_TransportException $e) {
                    $mailer->getTransport()->stop();
                    $error = $e;
                    return $this->render('AppBundle:Default:leadsendmail.html.twig', array('attachname' => $attachname, 'error' => $error, 'id' => $id, 'formsendmail' => $formsendmail->createView(), 'formsearch' => $formsearch->createView(), 'countlead' => $countlead, 'name' => $name, 'form' => $form->createView(),));
                } catch (Exception $e) {
                    $mailer->getTransport()->stop();
                    $error = $e;
                    return $this->render('AppBundle:Default:leadsendmail.html.twig', array('attachname' => $attachname, 'error' => $error, 'id' => $id, 'formsendmail' => $formsendmail->createView(), 'formsearch' => $formsearch->createView(), 'countlead' => $countlead, 'name' => $name, 'form' => $form->createView(),));
                }
            } else {
                $transport = \Swift_SmtpTransport::newInstance($smtp, $port, $mssl)
                        ->setUsername($euser)
                        ->setPassword($epass)
                        ->setAuthMode('PLAIN')
                ;

                $mailer = \Swift_Mailer::newInstance($transport);

                $message = \Swift_Message::newInstance($newsubject)
                        ->setFrom(array($euser => $fromname))
                        ->setTo(array($email => 'Client'))
                        ->setBcc(array('sent@dentistabroad.co.uk' => 'Sent'))
                        ->setBody($newbody, 'text/html')
                ;
                if ($path) {
                    $message->attach(\Swift_Attachment::fromPath($path));
                }


                try {
                    $mailer->getTransport()->start();
                    $mailer->send($message);
                    $mailer->getTransport()->stop();
                } catch (Swift_TransportException $e) {
                    $mailer->getTransport()->stop();
                    $error = $e;
                    return $this->render('AppBundle:Default:leadsendmail.html.twig', array('attachname' => $attachname, 'error' => $error, 'id' => $id, 'formsendmail' => $formsendmail->createView(), 'formsearch' => $formsearch->createView(), 'countlead' => $countlead, 'name' => $name, 'form' => $form->createView(),));
                } catch (Exception $e) {
                    $mailer->getTransport()->stop();
                    $error = $e;
                    return $this->render('AppBundle:Default:leadsendmail.html.twig', array('attachname' => $attachname, 'error' => $error, 'id' => $id, 'formsendmail' => $formsendmail->createView(), 'formsearch' => $formsearch->createView(), 'countlead' => $countlead, 'name' => $name, 'form' => $form->createView(),));
                }
            }
            $error = 'nan';
            $callhistory = new Callhistory();
            $callhistory->setLeadid($id);
            $callhistory->setCalldate(new \DateTime());
            $callhistory->setNote('Email sent: ' . $tempname);
            $callhistory->setAssign($name);
            $callhistory->setStatus('Email');
            $em->persist($callhistory);
            $em->flush();
        }
        return $this->render('AppBundle:Default:leadsendmail.html.twig', array('attachname' => $attachname, 'error' => $error, 'id' => $id, 'formsendmail' => $formsendmail->createView(), 'countlead' => $countlead, 'name' => $name,));
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
